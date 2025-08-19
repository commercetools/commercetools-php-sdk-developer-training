<?php

namespace App\Services;

use App\DTO\CustomerDTO;
use App\Services\ClientService;
use Commercetools\Api\Client\Resource\ResourceByProjectKey;
use Commercetools\Api\Models\Common\BaseAddressCollection;
use Commercetools\Api\Models\Customer\Customer;
use Commercetools\Api\Models\Customer\CustomerDraftBuilder;
use Commercetools\Api\Models\Customer\CustomerSigninBuilder;
use Commercetools\Api\Models\Customer\CustomerSignInResult;
use Commercetools\Api\Models\Customer\CustomerSignInResultBuilder;
use commercetools\Exception\ApiClientException;


class CustomersService
{
    private ResourceByProjectKey $api;

    public function __construct(private ClientService $clientService)
    {
        $this->api = $this->clientService->getApiClient();
    }

    /**
     * @param string $storeKey
     * @param string $customerId
     * @return Customer
     * @throws ApiClientException
     */
    public function getCustomerById(string $storeKey, string $customerId): Customer
    {
        return $this->api
            ->inStoreKeyWithStoreKeyValue($storeKey)
            ->customers()
            ->withId($customerId)
            ->get()
            ->execute();
    }

    /**
     * @param string $storeKey
     * @param string $key
     * @return Customer
     * @throws ApiClientException
     */
    public function getCustomerByKey(string $storeKey, string $key): Customer
    {
        return $this->api
            ->inStoreKeyWithStoreKeyValue($storeKey)
            ->customers()
            ->withKey($key)
            ->get()
            ->execute();
    }

    /**
     * @param string $storeKey
     * @param CustomerDTO $customerDTO
     * @return CustomerSignInResult
     * @throws ApiClientException
     */
    public function createCustomer(string $storeKey, CustomerDTO $customerDTO): CustomerSignInResult
    {
        // TODO: Create (signup) a customer and assign the anonymous cart from the request to them
        return CustomerSignInResultBuilder::of()->build();
    }

    /**
     * @param string $storeKey
     * @param CustomerDTO $customerDTO
     * @return CustomerSignInResult
     * @throws ApiClientException
     */
    public function loginCustomer(string $storeKey, CustomerDTO $customerDTO): CustomerSignInResult
    {
        // TODO: Login (signin) a customer and assign the anonymous cart from the request to them
        return CustomerSignInResultBuilder::of()->build();
    }

}
