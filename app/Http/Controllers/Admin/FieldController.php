<?php

namespace App\Http\Controllers\Admin; 
use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\Sport;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    // 1. Hiển thị danh sách sân + Bộ lọc tìm kiếm
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sportId = $request->input('sport_id');
        $status = $request->input('status');

        $query = Field::with(['sport', 'owner']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        if ($sportId) {
            $query->where('sport_id', $sportId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $fields = $query->orderBy('code', 'asc')->paginate(6)->withQueryString();
        $sports = Sport::all(); 
        
        $totalFieldsCount = Field::count();
        $maintenanceCount = Field::where('status', 'maintenance')->count();
        $occupancyRate = $totalFieldsCount > 0 ? 84 : 0;

        // 🌟 THUẬT TOÁN MỚI: Tính toán phản hồi trực tiếp theo giá sân hiện tại trên UI
        $averageRevenueStr = '0đ';
        if ($totalFieldsCount > 0) {
            // Lấy tổng giá tiền/giờ của tất cả các sân đang hoạt động
            $totalHourlyPrice = Field::where('status', 'active')->sum('price_per_hour');

            // Giả lập công thức: Mỗi sân hoạt động trung bình 4.5 giờ/ngày
            $estimatedDailyRevenue = $totalHourlyPrice * 4.5;

            // Tính mức doanh thu trung bình trên mỗi sân
            $avgDaily = $estimatedDailyRevenue / $totalFieldsCount;

            // Tự động rút gọn định dạng hiển thị sang M (Triệu) hoặc K (Ngàn)
            if ($avgDaily >= 1000000) {
                $averageRevenueStr = round($avgDaily / 1000000, 1) . 'M';
            } elseif ($avgDaily >= 1000) {
                $averageRevenueStr = round($avgDaily / 1000, 0) . 'K';
            } else {
                $averageRevenueStr = number_format($avgDaily) . 'đ';
            }
        }

        return view('admin.fields.index', compact(
            'fields', 'sports', 'totalFieldsCount', 'maintenanceCount', 'occupancyRate', 'averageRevenueStr'
        ));
    }

    // 2. Chuyển hướng sang trang sửa riêng biệt
    public function edit($id)
    {
        $field = Field::with('owner')->findOrFail($id);
        $sports = Sport::all();
        return view('admin.fields.edit', compact('field', 'sports'));
    }

    // 3. Xử lý đón dữ liệu chữ và lưu cập nhật
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:fields,code,' . $id,
            'name' => 'required|string|max:255',
            'sport_id' => 'required|exists:sports,id',
            'owner_name' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'status' => 'required|in:active,maintenance,inactive',
            'address' => 'required|string',
        ]);

        $field = Field::findOrFail($id);
        $ownerName = trim($request->input('owner_name'));

        $owner = User::where('name', $ownerName)->first();

        if (!$owner) {
            $ownerRole = Role::where('name', 'owner')->first();
            $roleId = $ownerRole ? $ownerRole->id : 1;

            $owner = User::create([
                'name' => $ownerName,
                'email' => 'owner_' . rand(100, 999) . time() . '@venuepro.com',
                'password' => bcrypt('123456'),
                'role_id' => $roleId
            ]);
        }

        $field->update([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'sport_id' => $request->input('sport_id'),
            'owner_id' => $owner->id,
            'price_per_hour' => $request->input('price_per_hour'),
            'status' => $request->input('status'),
            'address' => $request->input('address'),
        ]);

        return redirect('/admin/fields')->with('success', 'Cập nhật thông tin sân thành công!');
    }

    // 4. Xử lý xóa vĩnh viễn sân
    public function destroy($id)
    {
        $field = Field::findOrFail($id);
        $field->delete();

        return redirect()->back()->with('success', 'Đã xóa sân thành công!');
    }
}