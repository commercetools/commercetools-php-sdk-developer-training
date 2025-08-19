<?php

namespace App\Services;

use App\Services\ClientService;
use Commercetools\Api\Client\Resource\ResourceByProjectKey;
use Commercetools\Api\Models\Cart\Shipping;
use Commercetools\Api\Models\ShippingMethod\ShippingMethod;
use Commercetools\Api\Models\ShippingMethod\ShippingMethodBuilder;
use Commercetools\Api\Models\ShippingMethod\ShippingMethodPagedQueryResponse;
use Commercetools\Api\Models\ShippingMethod\ShippingMethodPagedQueryResponseBuilder;
use commercetools\Exception\ApiClientException;

// use Laravel\Pail\ValueObjects\Origin\Console;

class ShippingService
{
    private ResourceByProjectKey $api;

    public function __construct(private ClientService $clientService)
    {
        $this->api = $this->clientService->getApiClient();
    }

    /**
     * @return ShippingMethodPagedQueryResponse
     * @throws ApiClientException
     */
    public function getShippingMethods(): ShippingMethodPagedQueryResponse
    {
        return $this->api
            ->shippingMethods()
            ->get()
            ->execute();
    }

    /**
     * @param mixed $key
     * @return ShippingMethod
     * @throws ApiClientException
     */
    public function getShippingMethodByKey(string $key): ShippingMethod
    {
        // TODO: Implement the logic to return the shipping method by key
        return ShippingMethodBuilder::of()->build();
    }

    /**
     * @param string $location
     * @return ShippingMethodPagedQueryResponse
     * @throws ApiClientException
     */
    public function getShippingMethodsByLocation(string $location): ShippingMethodPagedQueryResponse
    {
        // TODO: Implement the logic to return a list of shipping methods valid for a country
        return ShippingMethodPagedQueryResponseBuilder::of()->build();
    }

    /**
     * @param string $key
     * @return bool
     * @throws ApiClientException
     */
    public function checkShippingMethodExistence(string $key): bool
    {

        try {
            // TODO: Check if the shipping method exists by making a HEAD request
            return true;
        } catch (ApiClientException $e) {
            if ($e->getCode() === 404) {
                return false;
            }
            throw $e;
        }
    }

/**
 * @param string $storeKey
 * @param string $cartId
 * @return ShippingMethodPagedQueryResponse
 * @throws ApiClientException
 */
    public function getShippingMethodsMatchingInStoreCart(string $storeKey, string $cartId): ShippingMethodPagedQueryResponse
    {
        return $this->api
            ->inStoreKeyWithStoreKeyValue($storeKey)
            ->shippingMethods()
            ->matchingCart()
            ->get()
            ->withCartId($cartId)
            ->execute();
    }

}
