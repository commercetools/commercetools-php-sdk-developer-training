<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\CartCreateRequest;
use App\Services\CartsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartsController extends Controller
{
    protected CartsService $cartsService;

    public function __construct(CartsService $cartsService)
    {
        $this->cartsService = $cartsService;
    }

    /**
     * GET /api/in-store/{storeKey}/carts/{id}
     */
    public function index(string $storeKey, string $id): JsonResponse
    {
        try {
            $cart = $this->cartsService->getCartById($storeKey, $id);
            return response()->json($cart);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to fetch cart',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/in-store/{storeKey}/carts
     */
    public function createCart(string $storeKey, CartCreateRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        try {
            $cart = $this->cartsService->createCart($storeKey, $dto);
            return response()->json($cart, 201);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to create cart',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/in-store/{storeKey}/carts/{id}
     */
    public function addLineItem(string $storeKey, string $id, CartCreateRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        try {
            $cart = $this->cartsService->addLineItem($storeKey, $id, $dto);
            return response()->json($cart, 200);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to add line item',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/in-store/{storeKey}/carts/{id}?discount-codes
     */
    public function addDiscountCode(string $storeKey, string $id, Request $request): JsonResponse
    {
        $code = $request->query('discountCode');
        try {
            $cart = $this->cartsService->addDiscountCode($storeKey, $id, $code);
            return response()->json($cart, 200);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to add discount code',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/in-store/{storeKey}/carts/{id}/shipping-address
     */
    public function setShippingAddress(string $storeKey, string $id, AddressRequest $request): JsonResponse
    {
        try {
            $cart = $this->cartsService->setShippingAddress($storeKey, $id, $request->toAddress());
            return response()->json($cart, 200);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to set shipping address',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/in-store/{storeKey}/carts/{id}/shipping-method?shippingMethodId
     */
    public function setShippingMethod(string $storeKey, string $id, Request $request): JsonResponse
    {
        $shippingMethodId = $request->query('shippingMethodId');
        try {
            $cart = $this->cartsService->setShippingMethod($storeKey, $id, $shippingMethodId);
            return response()->json($cart, 200);
        } catch (\Throwable $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to set shipping method',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
