<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sport;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\Sport\StoreSportRequest;  // Import Request Thêm
use App\Http\Requests\Admin\Sport\UpdateSportRequest; // Import Request Sửa

class SportController extends Controller
{
    /**
     * 1. Hiển thị trang danh sách môn thể thao
     */
    public function index()
    {
        $sports = Sport::all();
        return view('admin.sports.index', compact('sports'));
    }

    /**
     * 2. Xử lý bật/tắt trạng thái qua AJAX
     */
    public function toggleStatus(Request $request, $id)
    {
        $sport = Sport::findOrFail($id);
        $sport->is_active = $request->input('is_active');
        $sport->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật thành công!']);
    }

    /**
     * 3. Xử lý thêm mới môn thể thao (Tuân thủ chuẩn quy định nhóm)
     */
    public function store(StoreSportRequest $request)
    {
        $imageUrl = 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=500';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/sports'), $fileName);
            $imageUrl = '/uploads/sports/' . $fileName;
        }

        Sport::create([
            'name'        => $request->input('name'),
            'slug'        => Str::slug($request->input('name')), 
            'description' => $request->input('description'),
            'image'       => $imageUrl,
            'badge'       => 'Mới', 
            'is_active'   => true,
        ]);

        return redirect()->back();
    }

    /**
     * 4. Xử lý cập nhật dữ liệu (Tuân thủ chuẩn quy định nhóm)
     */
    public function update(UpdateSportRequest $request, $id)
    {
        $sport = Sport::findOrFail($id);

        $sport->name = $request->input('name');
        $sport->slug = Str::slug($request->input('name'));
        $sport->description = $request->input('description');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/sports'), $fileName);
            $sport->image = '/uploads/sports/' . $fileName;
        }

        $sport->save();

        return redirect()->back();
    }

    /**
     * 5. Xử lý xóa môn thể thao
     */
    public function destroy($id)
    {
        $sport = Sport::findOrFail($id);
        $sport->delete();

        return redirect()->back();
    }
}