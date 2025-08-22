<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomObjectRequest;
use App\Services\ExtensionsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ExtensionsController extends Controller
{
    protected ExtensionsService $extensionsService;

    public function __construct(ExtensionsService $extensionsService)
    {
        $this->extensionsService = $extensionsService;
    }

    /**
     * POST /api/extensions/types
     */
    public function createType(): JsonResponse
    {
        try {
            $type = $this->extensionsService->createType();
            return response()->json($type, 201);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to create custom type',
                'message' => $e,
            ], 500);
        }
    }

    /**
     * GET /api/extensions/custom-objects/{container}/{key}
     */
    public function index(string $container, string $key): JsonResponse
    {
        try {
            $customObject = $this->extensionsService->getCustomObjectByContainerAndKey($container, $key);
            return response()->json($customObject);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to fetch custom object',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/extensions/custom-objects
     */
    public function createCustomObject(CustomObjectRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        try {
            $customObject = $this->extensionsService->createCustomObject($dto);
            return response()->json($customObject, 201);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to create custom object',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
