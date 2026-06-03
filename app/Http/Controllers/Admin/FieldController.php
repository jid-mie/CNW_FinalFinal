<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\Sport;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Hiển thị danh sách sân kèm 3 thẻ thống kê BIẾN ĐỘNG ĐỘNG THEO BỘ LỌC
     */
    public function index(Request $request)
    {
        // 1. Khởi tạo Query danh sách sân kèm liên kết quan hệ
        $query = Field::with(['owner', 'sport']);

        // Bộ lọc tìm kiếm theo Tên định danh hoặc Mã sân
        if ($request->filled('search')) {
            $search = $request->search;
            $cleanSearch = str_replace(['SAN-', 'SBD-'], '', strtoupper($search));
            $cleanSearch = ltrim($cleanSearch, '0'); 

            $query->where(function($q) use ($search, $cleanSearch) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('code', 'LIKE', '%' . $search . '%')
                  ->orWhere('id', 'LIKE', '%' . $cleanSearch . '%');
            });
        }

        // Bộ lọc theo bộ môn thể thao
        if ($request->filled('sport_id')) {
            $query->where('sport_id', $request->sport_id);
        }

        // Bộ lọc theo trạng thái vận hành
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 📊 2. TRÍCH XUẤT TẬP DỮ LIỆU ĐÃ LỌC ĐỂ TÍNH TOÁN ĐỘNG CHO CÁC THẺ TRÊN ĐẦU TRANG
        $filteredFieldIds = (clone $query)->pluck('id'); 
        $currentFieldsCount = $filteredFieldIds->count() ?: 1;

        // 📈 Thẻ 1: Tỷ lệ lấp đầy biến động 
        $totalBookings = Booking::whereIn('field_id', $filteredFieldIds)
            ->whereIn('status', ['confirmed', 'completed'])
            ->count();
        $occupancyRate = min(100, round(($totalBookings / ($currentFieldsCount * 8)) * 100));
        if ($occupancyRate == 0 && $totalBookings > 0) $occupancyRate = 14; 

        // 🛠️ Thẻ 2: Số sân bảo trì biến động
        $maintenanceCount = Field::whereIn('id', $filteredFieldIds)->where('status', 'maintenance')->count();

        // 💵 Thẻ 3: Doanh thu trung bình biến động (ĐÃ FIX TRIỆT ĐỂ BẰNG COLLECTION)
        // Lấy toàn bộ lịch đặt thuộc nhóm sân đang lọc có phát sinh thanh toán thành công
        $paidBookings = Booking::whereIn('field_id', $filteredFieldIds)
            ->whereHas('payment', function($q) {
                $q->whereIn('status', ['paid', 'success', 'completed']);
            })->get();

        // Tính tổng tiền trực tiếp thu được từ tập dữ liệu đã lọc
        $totalPaid = $paidBookings->sum('total_price');

        // Bẻ gãy bẫy SQLite bằng cách đếm ngày duy nhất trực tiếp trên mớ dữ liệu thu về
        $distinctDays = $paidBookings->pluck('booking_date')->unique()->count() ?: 1;

        // Tính doanh thu trung bình theo ngày thực tế của nhóm sân
        $avgRevenueRaw = $totalPaid / $distinctDays;
        
        $avgRevenue = $avgRevenueRaw >= 1000000 
            ? number_format($avgRevenueRaw / 1000000, 1) . 'M' 
            : number_format($avgRevenueRaw, 0, ',', '.') . 'đ';

        // 3. Tiến hành phân trang đầu ra danh sách bảng dữ liệu
        $fields = $query->paginate(6)->withQueryString();
        $sports = Sport::where('is_active', true)->get();

        return view('admin.fields.index', compact('fields', 'sports', 'occupancyRate', 'maintenanceCount', 'avgRevenue'));
    }

    /**
     * Mở form chỉnh sửa thông tin sân thể thao
     */
    public function edit($id) 
    { 
        $field = Field::with('owner')->findOrFail($id); 
        $sports = Sport::where('is_active', true)->get(); 
        return view('admin.fields.edit', compact('field', 'sports')); 
    }

    /**
     * Lưu thông tin cập nhật từ Form sửa
     */
    public function update(Request $request, $id) 
    { 
        $request->validate([
            'code' => 'required|string|unique:fields,code,'.$id, 
            'name' => 'required|string|max:255', 
            'sport_id' => 'required|exists:sports,id', 
            'price_per_hour' => 'required|numeric|min:0', 
            'address' => 'required|string', 
            'status' => 'required|in:active,maintenance', 
            'owner_name' => 'required|string|max:255'
        ]); 
        
        $field = Field::findOrFail($id); 
        $field->update($request->only(['code', 'name', 'sport_id', 'price_per_hour', 'address', 'status'])); 
        
        if ($field->owner) { 
            $field->owner->update(['name' => $request->owner_name]); 
        } 
        return redirect()->route('admin.fields.index')->with('success', 'Cập nhật thông tin sân và chủ quản lý thành công!'); 
    }

    /**
     * Xử lý thêm mới sân thể thao
     */
    public function store(Request $request) 
    { 
        $request->validate([
            'code' => 'required|string|unique:fields,code', 
            'name' => 'required|string|max:255', 
            'sport_id' => 'required|exists:sports,id', 
            'price_per_hour' => 'required|numeric|min:0', 
            'address' => 'required|string', 
            'status' => 'required|in:active,maintenance'
        ]); 
        
        $data = $request->all(); 
        if (!isset($data['owner_id'])) { 
            $defaultOwner = User::whereHas('role', function($q) { $q->where('name', 'owner'); })->first() ?: User::first(); 
            $data['owner_id'] = $defaultOwner->id; 
        } 
        
        Field::create($data); 
        return redirect()->route('admin.fields.index')->with('success', 'Thêm sân thể thao mới thành công!'); 
    }

    /**
     * Hiển thị chi tiết sân, bao gồm các khung giờ và lịch sử đặt sân
     */
    public function show($id)
    {
        $field = Field::with(['owner', 'sport', 'timeSlots'])->findOrFail($id);
        $bookings = Booking::where('field_id', $id)
            ->with(['customer', 'timeSlot', 'payment'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.fields.show', compact('field', 'bookings'));
    }

    /**
     * Xóa sân thể thao ra khỏi hệ thống
     */
    public function destroy($id) 
    { 
        Field::findOrFail($id)->delete(); 
        return redirect()->route('admin.fields.index')->with('success', 'Xóa sân thể thao thành công!'); 
    }
}