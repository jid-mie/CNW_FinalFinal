<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $user = auth()->user();
        return view('customer.settings', compact('user'));
    }

    /**
     * Update user profile information
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
        ]);

        auth()->user()->update($validated);

        return back()->with('success', 'Thông tin cá nhân đã được cập nhật thành công.');
    }

    /**
     * Upload/update avatar
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();
        $name = 'customer_' . $user->id . '_' . time() . '.' . $request->file('avatar')->extension();
        $request->file('avatar')->move(public_path('uploads/avatars'), $name);
        $user->update(['avatar' => $name]);

        return back()->with('success', 'Cập nhật ảnh đại diện thành công.');
    }

    /**
     * Update language preference
     */
    public function updateLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|in:vi,en',
        ]);

        auth()->user()->update([
            'language_preference' => $request->language,
        ]);

        return back()->with('success', 'Ngôn ngữ đã được cập nhật.');
    }

    /**
     * Update theme preference
     */
    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        auth()->user()->update([
            'theme_preference' => $request->theme,
        ]);

        return back()->with('success', 'Chủ đề đã được cập nhật.');
    }
}
