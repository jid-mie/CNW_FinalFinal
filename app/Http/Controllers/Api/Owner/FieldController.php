<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Owner\StoreFieldRequest;
use App\Http\Requests\Api\Owner\UpdateFieldRequest;
use App\Http\Resources\FieldResource;
use App\Models\Field;
use Cloudinary\Cloudinary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    private Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key' => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->fields()
            ->with(['sport', 'timeSlots'])
            ->withCount(['timeSlots', 'bookings']);

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sport_id')) {
            $query->where('sport_id', $request->sport_id);
        }

        $sortField = $request->sort_field ?? 'created_at';
        $sortDir = $request->sort_dir === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['name', 'price_per_hour', 'status', 'created_at', 'updated_at'];
        $sortField = in_array($sortField, $allowedSorts) ? $sortField : 'created_at';

        $query->orderBy($sortField, $sortDir);

        $fields = $query->paginate($request->per_page ?? 10);

        return $this->successResponse(
            FieldResource::collection($fields)->response()->getData(true),
            'Danh sách sân của bạn'
        );
    }

    public function show(Request $request, Field $field): JsonResponse
    {
        if ($field->owner_id !== $request->user()->id) {
            return $this->errorResponse('Bạn không có quyền xem sân này', 403);
        }

        $field->load(['sport', 'timeSlots' => fn ($q) => $q->orderBy('start_time')]);

        return $this->successResponse(
            new FieldResource($field),
            'Chi tiết sân'
        );
    }

    public function store(StoreFieldRequest $request): JsonResponse
    {
        $field = $request->user()->fields()->create(
            $request->validated()
        );

        return $this->successResponse(
            new FieldResource($field->load('sport')),
            'Thêm sân thành công',
            201
        );
    }

    public function update(UpdateFieldRequest $request, Field $field): JsonResponse
    {
        if ($field->owner_id !== $request->user()->id) {
            return $this->errorResponse('Bạn không có quyền sửa sân này', 403);
        }

        $field->update($request->validated());

        return $this->successResponse(
            new FieldResource($field->fresh()->load('sport')),
            'Cập nhật sân thành công'
        );
    }

    public function destroy(Request $request, Field $field): JsonResponse
    {
        if ($field->owner_id !== $request->user()->id) {
            return $this->errorResponse('Bạn không có quyền xoá sân này', 403);
        }

        // Delete image from Cloudinary
        if ($field->image) {
            $this->deleteFromCloudinary($field->image);
        }

        $field->delete();

        return $this->successResponse(null, 'Xoá sân thành công');
    }

    public function uploadImage(Request $request, Field $field): JsonResponse
    {
        if ($field->owner_id !== $request->user()->id) {
            return $this->errorResponse('Bạn không có quyền thao tác trên sân này', 403);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Delete old image on Cloudinary
        if ($field->image) {
            $this->deleteFromCloudinary($field->image);
        }

        $publicId = 'fields/field_'.$field->id.'_'.time();

        $result = $this->cloudinary->uploadApi()->upload(
            $request->file('image')->getRealPath(),
            [
                'public_id' => $publicId,
                'overwrite' => true,
                'resource_type' => 'image',
            ]
        );

        $imageUrl = $result['secure_url'];
        $imagePublicId = $result['public_id'];

        $field->update([
            'image' => $imagePublicId,
            'image_url' => $imageUrl,
        ]);

        return $this->successResponse([
            'image' => $imagePublicId,
            'image_url' => $imageUrl,
        ], 'Tải ảnh lên thành công');
    }

    public function deleteImage(Request $request, Field $field): JsonResponse
    {
        if ($field->owner_id !== $request->user()->id) {
            return $this->errorResponse('Bạn không có quyền thao tác trên sân này', 403);
        }

        if (! $field->image) {
            return $this->errorResponse('Sân này chưa có ảnh', 404);
        }

        $this->deleteFromCloudinary($field->image);

        $field->update(['image' => null, 'image_url' => null]);

        return $this->successResponse(null, 'Xoá ảnh thành công');
    }

    private function deleteFromCloudinary(string $publicId): void
    {
        try {
            $this->cloudinary->uploadApi()->destroy($publicId);
        } catch (\Exception $e) {
            // Log error but don't block the request
            logger()->warning('Cloudinary delete failed: '.$e->getMessage(), [
                'public_id' => $publicId,
            ]);
        }
    }
}
