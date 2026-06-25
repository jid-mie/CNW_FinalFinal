<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('admin.settings.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        auth()->user()->update($data);

        return back()->with('success', 'Cập nhật thông tin thành công');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();
        $name = 'admin_'.$user->id.'_'.time().'.'.$request->file('avatar')->extension();
        $request->file('avatar')->move(public_path('uploads/avatars'), $name);
        $user->update(['avatar' => $name]);

        return back()->with('success', 'Cập nhật ảnh đại diện thành công');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công');
    }

    public function updateLanguage(Request $request)
    {
        $request->validate(['language' => 'required|in:vi,en']);

        auth()->user()->update(['language_preference' => $request->language]);

        return back()->with('success', 'Cập nhật ngôn ngữ thành công');
    }

    public function updateTheme(Request $request)
    {
        $request->validate(['theme' => 'required|in:light,dark']);

        auth()->user()->update(['theme_preference' => $request->theme]);

        return back()->with('success', 'Cập nhật chủ đề thành công');
    }
}