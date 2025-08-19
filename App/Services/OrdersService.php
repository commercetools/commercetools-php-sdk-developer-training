<?php

namespace App\Services;

use App\DTO\OrderCustomFieldsDTO;
use App\DTO\OrderDTO;
use App\Services\ClientService;
use Commercetools\Api\Client\Resource\ResourceByProjectKey;
use Commercetools\Api\Models\Cart\CartResourceIdentifierBuilder;
use Commercetools\Api\Models\Order\Order;
use Commercetools\Api\Models\Order\OrderFromCartDraftBuilder;
use Commercetools\Api\Models\Order\OrderSetCustomTypeActionBuilder;
use Commercetools\Api\Models\Order\OrderUpdateActionCollection;
use Commercetools\Api\Models\Order\OrderUpdateBuilder;
use Commercetools\Api\Models\Type\FieldContainerBuilder;
use Commercetools\Api\Models\Type\TypeResourceIdentifierBuilder;
use Illuminate\Support\Str;
use commercetools\Exception\ApiClientException;


class OrdersService
{
    private ResourceByProjectKey $api;

    public function __construct(private ClientService $clientService)
    {
        $this->api = $this->clientService->getApiClient();
    }

    /**
     * @param string $storeKey
     * @param string $orderId
     * @return Order
     * @throws ApiClientException
     */
    public function getOrderById(string $storeKey, string $orderId): Order
    {
        return $this->api
            ->inStoreKeyWithStoreKeyValue($storeKey)
            ->orders()
            ->withId($orderId)
            ->get()
            ->execute();
    }

        /**
     * @param string $storeKey
     * @param string $orderNumber
     * @return Order
     * @throws ApiClientException
     */
    public function getOrderByOrderNumber(string $storeKey, string $orderNumber): Order
    {
        return $this->api
            ->inStoreKeyWithStoreKeyValue($storeKey)
            ->orders()
            ->withOrderNumber($orderNumber)
            ->get()
            ->execute();
    }

    /**
     * @param string $storeKey
     * @param OrderDTO $orderDTO
     * @return Order
     * @throws ApiClientException
     */
    public function createOrder(string $storeKey, OrderDTO $orderDTO): Order
    {
        return $this->api
            ->inStoreKeyWithStoreKeyValue($storeKey)
            ->orders()
            ->post(OrderFromCartDraftBuilder::of()
                ->withCart(CartResourceIdentifierBuilder::of()
                    ->withId($orderDTO->cartId)
                    ->build())
                ->withVersion($orderDTO->cartVersion)
                ->withOrderNumber('ORD-' . strtoupper(Str::random(6)))
                ->build()
            )
            ->execute();
    }

    /**
     * @param string $storeKey
     * @param string $orderNumber
     * @param OrderCustomFieldsDTO $customFields
     * @return Order
     * @throws ApiClientException
     */
    public function updateOrderCustomFields(
        string $storeKey,
        string $orderNumber,
        OrderCustomFieldsDTO $customFields): Order
    {
        $order = $this->getOrderByOrderNumber($storeKey, $orderNumber);

        $orderUpdateActions = OrderUpdateActionCollection::of()
            ->add(OrderSetCustomTypeActionBuilder::of()
                ->withType(TypeResourceIdentifierBuilder::of()
                    ->withKey('delivery-instructions11')
                    ->build())
                ->withFields(FieldContainerBuilder::of()
                    ->put('instructions', $customFields->instructions)
                    ->put('time', $customFields->time)
                    ->build())
                ->build());
        return $this->api
            ->inStoreKeyWithStoreKeyValue($storeKey)
            ->orders()
            ->withOrderNumber($orderNumber)
            ->post(OrderUpdateBuilder::of()
                ->withVersion($order->getVersion())
                ->withActions($orderUpdateActions)
                ->build()
            )
            ->execute();
    }
}
