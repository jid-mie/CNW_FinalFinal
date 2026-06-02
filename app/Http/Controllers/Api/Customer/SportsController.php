<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\SportResource;
use App\Models\Sport;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SportsController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $sports = Sport::where('is_active', true)->get();

        return $this->successResponse(
            SportResource::collection($sports),
            'Sports retrieved successfully'
        );
    }

    public function show(Sport $sport): JsonResponse
    {
        if (! $sport->is_active) {
            return $this->errorResponse('Sport not found', 404);
        }

        return $this->successResponse(
            new SportResource($sport),
            'Sport retrieved successfully'
        );
    }
}
