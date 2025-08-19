<?php

namespace App\Services;

use App\Services\ClientService;
use Commercetools\Api\Client\Resource\ResourceByProjectKey;
use Commercetools\Api\Models\ShippingMethod\ShippingMethod;
use Commercetools\Api\Models\ShippingMethod\ShippingMethodPagedQueryResponse;
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
        return $this->api
            ->shippingMethods()
            ->withKey($key)
            ->get()
            ->execute();
    }

    /**
     * @param string $location
     * @return ShippingMethodPagedQueryResponse
     * @throws ApiClientException
     */
    public function getShippingMethodsByLocation(string $location): ShippingMethodPagedQueryResponse
    {
        return $this->api
            ->shippingMethods()
            ->matchingLocation()
            ->get()
            ->withCountry($location)
            ->execute();
    }

    /**
     * @param string $key
     * @return bool
     * @throws ApiClientException
     */
    public function checkShippingMethodExistence(string $key): bool
    {
       echo "Checking existence of shipping method with key: $key";
        try {
            $this->api
                ->shippingMethods()
                ->withKey($key)
                ->head()
                ->execute();
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
