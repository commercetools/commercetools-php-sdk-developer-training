<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCustomFieldsRequest;
use App\Http\Requests\OrderRequest;
use App\Services\OrdersService;
use Illuminate\Http\JsonResponse;

class OrdersController extends Controller
{
    protected OrdersService $ordersService;

    public function __construct(OrdersService $ordersService)
    {
        $this->ordersService = $ordersService;
    }

    /**
     * GET /api/in-store/{storeKey}/orders/{id}
     */
    public function index(string $storeKey, string $id): JsonResponse
    {
        try {
            $order = $this->ordersService->getOrderById($storeKey, $id);
            return response()->json($order);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to fetch order',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/in-store/{storeKey}/orders
     */
    public function createOrder(string $storeKey, OrderRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        try {
            $order = $this->ordersService->createOrder($storeKey, $dto);
            return response()->json($order, 201);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to create order',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

        /**
     * POST /api/in-store/{storeKey}/orders/{orderNumber}/custom-fields
     */
    public function updateOrderCustomFields(string $storeKey, string $orderNumber, OrderCustomFieldsRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        try {
            $order = $this->ordersService->updateOrderCustomFields($storeKey, $orderNumber, $dto);
            return response()->json($order, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to update order custom fields',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
