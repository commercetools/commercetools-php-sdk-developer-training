<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\GraphQLService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GraphQLController extends Controller
{
    protected GraphQLService $graphQLService;

    public function __construct(GraphQLService $graphQLService)
    {
        $this->graphQLService = $graphQLService;
    }

    /**
     * POST /api/graphql/orders?email=${email}&locale=${locale}
     */
    public function index(Request $request): JsonResponse
    {
        $email = $request->query('email');
        $locale = $request->query('locale', 'en-US');
        try {
            $response = $this->graphQLService->getOrdersByCustomerEmail($email, $locale);
            return response()->json($response, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to fetch orders',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
