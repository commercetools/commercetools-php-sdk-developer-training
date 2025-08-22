<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\ProductsService;
use Illuminate\Support\Facades\Log;

class ProductsController extends Controller
{
    protected ProductsService $productsService;

    public function __construct(ProductsService $productsService)
    {
        $this->productsService = $productsService;
    }

    /**
     * GET /api/products/search
     */
    public function search(SearchRequest $searchRequest): JsonResponse
    {
        $dto = $searchRequest->toDto();

        try {
            $products = $this->productsService->getProducts($dto);
            return response()->json($products);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to fetch products',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
