<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Services\CustomersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    protected CustomersService $customersService;

    public function __construct(CustomersService $customersService)
    {
        $this->customersService = $customersService;
    }

    /**
     * GET /api/in-store/{storeKey}/customers/{id}
     */
    public function getCustomerById(string $storeKey, string $id): JsonResponse
    {
        try {
            $customer = $this->customersService->getCustomerById($storeKey, $id);
            return response()->json($customer);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to fetch customer',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/in-store/{storeKey}/customers
     */
    public function signupCustomer(string $storeKey, CustomerRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        try {
            $customer = $this->customersService->createCustomer($storeKey, $dto);
            return response()->json($customer, 201);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to create customer',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/in-store/{storeKey}/customers/login
     */
    public function loginCustomer(string $storeKey, CustomerRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        try {
            $customer = $this->customersService->loginCustomer($storeKey, $dto);
            return response()->json($customer, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to login customer',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
