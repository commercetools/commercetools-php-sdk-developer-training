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

        $customerDraft = CustomerDraftBuilder::of()
            ->withKey($customerDTO->key)
            ->withEmail($customerDTO->email)
            ->withFirstName($customerDTO->firstName)
            ->withLastName($customerDTO->lastName)
            ->withPassword($customerDTO->password)
            ->withAddresses(BaseAddressCollection::of()
                ->add($customerDTO->address)
            );

        if ($customerDTO->isDefaultShippingAddress) {
            $customerDraft = $customerDraft->withDefaultShippingAddress(0);
        }
        if ($customerDTO->isDefaultBillingAddress) {
            $customerDraft = $customerDraft->withDefaultBillingAddress(0);
        }

        if ($customerDTO->anonymousCartId !== null) {
            $customerDraft = $customerDraft->withAnonymousCartId($customerDTO->anonymousCartId);
        }

        return $this->api
            ->inStoreKeyWithStoreKeyValue($storeKey)
            ->customers()
            ->post($customerDraft->build())
            ->execute();
    }

    /**
     * @param string $storeKey
     * @param CustomerDTO $customerDTO
     * @return CustomerSignInResult
     * @throws ApiClientException
     */
    public function loginCustomer(string $storeKey, CustomerDTO $customerDTO): CustomerSignInResult
    {
        $customerSignInBuilder = CustomerSigninBuilder::of()
            ->withEmail($customerDTO->email)
            ->withPassword($customerDTO->password);

        if ($customerDTO->anonymousCartId !== null) {
            $customerSignInBuilder = $customerSignInBuilder->withAnonymousCartId($customerDTO->anonymousCartId)
                ->withAnonymousCartSignInMode("merge");
        }

        return $this->api
            ->inStoreKeyWithStoreKeyValue($storeKey)
            ->login()
            ->post($customerSignInBuilder->build())
            ->execute();
    }

}
