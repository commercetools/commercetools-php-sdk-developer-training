<?php

namespace App\Services;


use App\Services\ClientService;
use Commercetools\Api\Client\Resource\ResourceByProjectKey;
use Commercetools\Api\Models\GraphQl\GraphQLRequestBuilder;
use Commercetools\Api\Models\GraphQl\GraphQLResponse;
use Commercetools\Api\Models\GraphQl\GraphQLVariablesMapBuilder;
use Commercetools\Exception\ApiClientException;

class GraphQLService
{
    private ResourceByProjectKey $api;

    public function __construct(private ClientService $clientService)
    {
        $this->api = $this->clientService->getApiClient();
    }

    /**
     * @param string $storeKey
     * @param string $email
     * @return GraphQLResponse
     * @throws ApiClientException
     */
    public function getOrdersByCustomerEmail(string $email, string $locale): GraphQLResponse
    {
        $query = <<<GRAPHQL
        query {
            orders(where: "customerEmail=\"$email\"") {
                results {
                    customerEmail
                    customer {
                        firstName
                        lastName
                    }
                    lineItems {
                        name(locale: "$locale")
                        totalPrice {centAmount currencyCode}
                    }
                    totalPrice {
                        centAmount
                        currencyCode
                    }
                }
            }
        }
        GRAPHQL;

        $graphqlRequest = GraphQLRequestBuilder::of()
            ->withQuery($query)
            ->build();

        return $this->api
            ->graphql()
            ->post($graphqlRequest)
            ->execute();
    }

}
