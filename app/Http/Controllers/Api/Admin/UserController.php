<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Store a newly created user (Customer or Owner) in storage.
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return $this->successResponse(
            new UserResource($user->load('role')),
            'Tài khoản đã được tạo thành công.',
            201
        );
    }
}
