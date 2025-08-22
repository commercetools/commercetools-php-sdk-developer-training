<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\ImportsService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ImportsController extends Controller
{
    protected ImportsService $importsService;

    public function __construct(ImportsService $importsService)
    {
        $this->importsService = $importsService;
    }

    /**
     * GET /api/imports/summary
     */
    public function index(): JsonResponse
    {
        try {
            $summary = $this->importsService->getImportSummary();
            return response()->json($summary);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to fetch import summary',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/imports/products
     */
    public function importProducts(Request $request): JsonResponse
    {
        $csvFile = $request->file('file');
        try {
            $response = $this->importsService->importProducts($csvFile);
            if ($response->getOperationStatus() === null) {
                return response()->json([
                    'message' => 'Not implemented.',
                ], 501);
            }
            return response()->json($response, 200);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to import products',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
