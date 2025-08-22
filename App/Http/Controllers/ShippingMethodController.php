<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingMethodController extends Controller
{
    protected ShippingService $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * GET /api/shipping-methods
     */
    public function index(): JsonResponse
    {
        try {
            $methods = $this->shippingService->getShippingMethods();
            return response()->json($methods);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to fetch shipping methods',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/shipping-methods/matching-location?country={country}
     */
    public function getShippingMethodsByLocation(Request $request): JsonResponse
    {
        $country = $request->query('country');
        if (!$country) {
            return response()->json(['error' => 'Country parameter is required'], 400);
        }
        try {
            $methods = $this->shippingService->getShippingMethodsByLocation($country);
            if ($methods->getResults() === null) {
                return response()->json([
                    'message' => 'Not implemented.',
                ], 501);
            }
            if ($methods->getCount() === 0) {
                return response()->json([
                    'message' => 'No shipping methods found for the specified location.',
                ], 404);
            }
            return response()->json($methods);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to fetch shipping methods',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/shipping-methods/{key}
     */
    public function getShippingMethodByKey(string $key): JsonResponse
    {
        try {
            $method = $this->shippingService->getShippingMethodByKey($key);
            if ($method->getVersion() === null) {
                return response()->json([
                    'message' => 'Not implemented.',
                ], 501);
            }
            return response()->json($method);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to fetch shipping method',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * HEAD /api/shipping-methods/{key}
     */
    public function checkShippingMethodExistence(string $key): JsonResponse
    {
        try {
            $exists = $this->shippingService->checkShippingMethodExistence($key);
            return $exists ? response()->json(true, 200) : response()->json(false, 404);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to check shipping method existence',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/in-store/{storeKey}/shipping-methods/matching-cart?cartId={cartId}
     */
    public function getShippingMethodsMatchingInStoreCart(string $storeKey, Request $request): JsonResponse
    {
        try {
            $cartId = $request->query('cartId'); // ✅ retrieve cartId from query string

            if (!$cartId) {
                return response()->json([
                    'error' => 'Missing required cartId query parameter.'
                ], 400);
            }
            $methods = $this->shippingService->getShippingMethodsMatchingInStoreCart($storeKey, $cartId);
            return response()->json($methods);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to fetch matching shipping methods',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
