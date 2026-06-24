<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SportController extends Controller
{
    /**
     * Hiển thị danh sách toàn bộ môn thể thao
     */
    public function index()
    {
        $sports = Sport::orderBy('id', 'desc')->get();

        return view('admin.sports.index', compact('sports'));
    }

    /**
     * 🎯 ĐÃ BỔ SUNG: Mở Form trang thêm mới môn thể thao
     */
    public function add()
    {
        return view('admin.sports.add');
    }

    /**
     * Xử lý thêm mới môn thể thao vào hệ thống từ Form Create
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sports,name',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('sports', 'public');
            $imageUrl = 'storage/'.$path;
        }

        Sport::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image_url' => $imageUrl,
            'is_active' => true,
        ]);

        return redirect()->route('admin.sports.index')->with('success', 'Thêm môn thể thao mới thành công!');
    }

    /**
     * Mở Form chỉnh sửa thông tin môn thể thao cụ thể
     */
    public function edit($id)
    {
        $sport = Sport::findOrFail($id);

        return view('admin.sports.edit', compact('sport'));
    }

    /**
     * Cập nhật thông tin thay đổi từ Form sửa bộ môn
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $sport = Sport::findOrFail($id);

        $imageUrl = $request->filled('image_url') ? $request->image_url : $sport->image_url;
        if ($request->hasFile('image_file')) {
            // Delete old file if it was uploaded to storage
            if ($sport->image_url) {
                $pathOnly = $sport->image_url;
                if (str_starts_with($sport->image_url, 'http://') || str_starts_with($sport->image_url, 'https://')) {
                    $pathOnly = parse_url($sport->image_url, PHP_URL_PATH);
                }
                $oldPath = $pathOnly;
                if (str_contains($oldPath, 'storage/')) {
                    $oldPath = substr($oldPath, strpos($oldPath, 'storage/') + 8);
                }
                $oldPath = ltrim($oldPath, '/');

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('image_file')->store('sports', 'public');
            $imageUrl = 'storage/'.$path;
        }

        $sport->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image_url' => $imageUrl,
        ]);

        return redirect()->route('admin.sports.index')->with('success', 'Cập nhật thông tin môn thể thao thành công!');
    }

    /**
     * Xử lý bật/tắt nhanh trạng thái vận hành qua AJAX (Nút gạt toggle trên Card)
     */
    public function toggleStatus($id)
    {
        $sport = Sport::findOrFail($id);
        $sport->is_active = ! $sport->is_active;
        $sport->save();

        return response()->json([
            'success' => true,
            'is_active' => $sport->is_active,
            'message' => 'Cập nhật trạng thái bộ môn thành công!',
        ]);
    }

    /**
     * Xóa môn thể thao ra khỏi hệ thống
     */
    public function destroy($id)
    {
        $sport = Sport::findOrFail($id);
        $sport->delete();

        return redirect()->route('admin.sports.index')->with('success', 'Xóa môn thể thao thành công!');
    }
}
