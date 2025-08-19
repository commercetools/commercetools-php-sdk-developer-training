<?php

namespace App\Services;

use App\DTO\CartDTO;
use App\Services\ClientService;
use Commercetools\Api\Client\Resource\ResourceByProjectKey;
use Commercetools\Api\Models\Cart\Cart;
use Commercetools\Api\Models\Cart\CartAddDiscountCodeActionBuilder;
use Commercetools\Api\Models\Cart\CartAddLineItemActionBuilder;
use Commercetools\Api\Models\Cart\CartBuilder;
use Commercetools\Api\Models\Cart\CartDraftBuilder;
use Commercetools\Api\Models\Cart\CartSetCustomerEmailActionBuilder;
use Commercetools\Api\Models\Cart\CartSetShippingAddressActionBuilder;
use Commercetools\Api\Models\Cart\CartSetShippingMethodActionBuilder;
use Commercetools\Api\Models\Cart\CartUpdateActionCollection;
use Commercetools\Api\Models\Cart\CartUpdateBuilder;
use Commercetools\Api\Models\Cart\LineItemDraftBuilder;
use Commercetools\Api\Models\Cart\LineItemDraftCollection;
use Commercetools\Api\Models\Common\AddressModel;
use Commercetools\Api\Models\ShippingMethod\ShippingMethodResourceIdentifierBuilder;
use commercetools\Exception\ApiClientException;


class CartsService
{
    private ResourceByProjectKey $api;

    public function __construct(private ClientService $clientService)
    {
        $this->api = $this->clientService->getApiClient();
    }

    /**
     * @param string $storeKey
     * @param string $cartId
     * @return Cart
     * @throws ApiClientException
     */
    public function getCartById(string $storeKey, string $cartId): Cart
    {
        return $this->api
            ->inStoreKeyWithStoreKeyValue($storeKey)
            ->carts()
            ->withId($cartId)
            ->get()
            ->execute();
    }

    /**
     * @param string $storeKey
     * @param CartDTO $CartDTO
     * @return Cart
     * @throws ApiClientException
     */
    public function createCart(string $storeKey, CartDTO $CartDTO): Cart
    {
        // TODO: Create a cart with anonymousId and add SKU as a line item
        return CartBuilder::of()->build();
    }

    /**
     * @param string $storeKey
     * @param string $id
     * @param CartDTO $CartDTO
     * @return Cart
     * @throws ApiClientException
     */
    public function addLineItem(string $storeKey, string $id, CartDTO $CartDTO): Cart
    {
        // TODO: Add SKU to the cart
        return CartBuilder::of()->build();
    }

    /**
     * @param string $storeKey
     * @param string $id
     * @param string $code
     * @return Cart
     * @throws ApiClientException
     */
    public function addDiscountCode(string $storeKey, string $id, string $code): Cart
    {
        $cart = $this->getCartById($storeKey, $id);
        return $this->api
            ->inStoreKeyWithStoreKeyValue($storeKey)
            ->carts()
            ->withId($id)
            ->post(CartUpdateBuilder::of()
                ->withActions(CartUpdateActionCollection::of()
                    ->add(CartAddDiscountCodeActionBuilder::of()
                        ->withCode($code)
                        ->build()
                    ))
                ->withVersion($cart->getVersion())
                ->build()
            )
            ->execute();
    }

    /**
     * @param string $storeKey
     * @param string $id
     * @param AddressModel $address
     * @return Cart
     * @throws ApiClientException
     */
    public function setShippingAddress(string $storeKey, string $id, AddressModel $address): Cart
    {
        // TODO: Set Shipping address on the cart
        // TODO: Set email on the cart
        return CartBuilder::of()->build();
    }

    /**
     * @param string $storeKey
     * @param string $id
     * @param string $shippingMethodId
     * @return Cart
     * @throws ApiClientException
     */
    public function setShippingMethod(string $storeKey, string $id, string $shippingMethodId): Cart
    {
        $cart = $this->getCartById($storeKey, $id);

        return $this->api
            ->inStoreKeyWithStoreKeyValue($storeKey)
            ->carts()
            ->withId($id)
            ->post(CartUpdateBuilder::of()
                ->withActions(CartUpdateActionCollection::of()
                    ->add(CartSetShippingMethodActionBuilder::of()
                        ->withShippingMethod(ShippingMethodResourceIdentifierBuilder::of()
                            ->withId($shippingMethodId)
                            ->build()
                        )
                        ->build()
                    ))
                ->withVersion($cart->getVersion())
                ->build()
            )
            ->execute();
    }

}
