<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * GET /api/project/countries
     */
    public function index(): JsonResponse
    {
        try {
            $countries = $this->projectService->getCountries();
            return response()->json($countries);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to fetch countries',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/project/stores
     */
    public function getStores(): JsonResponse
    {

        try {
            $stores = $this->projectService->getStores();
            return response()->json($stores);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to fetch stores',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
