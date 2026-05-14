<?php

namespace App\Http\Controllers\Api;

use App\Application\User\DTOs\CreateUserData;
use App\Application\User\UseCases\CreateUserUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UserController extends Controller
{
    public function store(CreateUserRequest $request, CreateUserUseCase $useCase): JsonResponse
    {
        $user = $useCase->execute(new CreateUserData(
            name: $request->string('name')->toString(),
            email: $request->string('email')->toString(),
            password: $request->string('password')->toString(),
        ));

        return response()->json([
            'data' => $user,
        ], Response::HTTP_CREATED);
    }
}
