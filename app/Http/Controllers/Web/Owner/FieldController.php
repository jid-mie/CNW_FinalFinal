<?php

namespace App\Http\Controllers\Web\Owner;

use App\Models\Field;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class FieldController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->fields()->with('sport')->withCount(['timeSlots', 'bookings']);

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('sport_id')) {
            $query->where('sport_id', $request->sport_id);
        }

        $sortField = in_array($request->sort_field, ['name', 'price_per_hour', 'status', 'created_at']) ? $request->sort_field : 'created_at';
        $sortDir = $request->sort_dir === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDir);

        $fields = $query->paginate(10)->withQueryString();
        $sports = Sport::all();

        return view('owner.fields.index', compact('fields', 'sports'));
    }

    public function create()
    {
        $sports = Sport::all();

        return view('owner.fields.create', compact('sports'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sport_id' => 'required|exists:sports,id',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:500',
            'price_per_hour' => 'required|numeric|min:0',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'status' => 'nullable|in:active,inactive',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('fields', 'public');
            $data['image_url'] = 'storage/'.$data['image'];
        }

        auth()->user()->fields()->create($data);

        return redirect()->route('owner.fields.index')->with('success', 'Thêm sân thành công');
    }

    public function edit(Field $field)
    {
        if ($field->owner_id !== auth()->id()) {
            abort(403);
        }
        $sports = Sport::all();

        return view('owner.fields.edit', compact('field', 'sports'));
    }

    public function update(Request $request, Field $field)
    {
        if ($field->owner_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sport_id' => 'required|exists:sports,id',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:500',
            'price_per_hour' => 'required|numeric|min:0',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'status' => 'nullable|in:active,inactive',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($field->image) {
                Storage::disk('public')->delete($field->image);
            }
            $data['image'] = $request->file('image')->store('fields', 'public');
            $data['image_url'] = 'storage/'.$data['image'];
        }

        $field->update($data);

        return redirect()->route('owner.fields.index')->with('success', 'Cập nhật sân thành công');
    }

    public function destroy(Field $field)
    {
        if ($field->owner_id !== auth()->id()) {
            abort(403);
        }
        if ($field->image) {
            Storage::disk('public')->delete($field->image);
        }
        $field->delete();

        return redirect()->route('owner.fields.index')->with('success', 'Xoá sân thành công');
    }

    public function toggleStatus(Request $request, Field $field)
    {
        if ($field->owner_id !== auth()->id()) {
            abort(403);
        }
        $newStatus = $field->status === 'active' ? 'inactive' : 'active';
        $field->update(['status' => $newStatus]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $field->status,
                'message' => 'Cập nhật trạng thái sân thành công!',
            ]);
        }

        return back()->with('success', 'Đã cập nhật trạng thái sân');
    }
}
