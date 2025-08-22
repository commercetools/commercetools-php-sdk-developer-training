<?php

namespace App\Services;

use App\DTO\SearchDTO;
use App\Services\ClientService;
use Commercetools\Api\Client\Resource\ResourceByProjectKey;
use Commercetools\Api\Models\ProductSearch\ProductPagedSearchResponse;
use Commercetools\Api\Models\ProductSearch\ProductSearchFacetDistinctExpressionBuilder;
use Commercetools\Api\Models\ProductSearch\ProductSearchFacetDistinctValueBuilder;
use commercetools\Exception\ApiClientException;
use Commercetools\Api\Models\ProductSearch\ProductSearchRequestBuilder;
use Commercetools\Api\Models\Search\SearchSortingBuilder;
use Commercetools\Api\Models\Search\SearchSortingCollection;
use Commercetools\Api\Models\ProductSearch\ProductSearchFacetExpressionCollection;
use Commercetools\Api\Models\ProductSearch\ProductSearchProjectionParamsBuilder;
use Commercetools\Api\Models\Search\SearchAndExpressionBuilder;
use Commercetools\Api\Models\Search\SearchExactExpressionBuilder;
use Commercetools\Api\Models\Search\SearchExactValueBuilder;
use Commercetools\Api\Models\Search\SearchQuery;


class ProductsService
{
    private ResourceByProjectKey $api;

    public function __construct(private ClientService $clientService)
    {
        $this->api = $this->clientService->getApiClient();
    }

    /**
     * @return ProductPagedSearchResponse
     * @throws ApiClientException
     */
    public function getProducts(SearchDTO $SearchDTO): ProductPagedSearchResponse
    {
        $productSearchRequest = ProductSearchRequestBuilder::of()
            ->withSort(SearchSortingCollection::of()
                ->add(SearchSortingBuilder::of()
                    ->withField("variants.prices.centAmount")
                    ->withOrder("asc")
                    ->withMode("min")
                    ->build()
                )
            )
            ->withProductProjectionParameters(productProjectionParameters: ProductSearchProjectionParamsBuilder::of()
                ->withStoreProjection($SearchDTO->storeKey)
                ->withPriceCurrency($SearchDTO->currency)
                ->build()
            )
            ->withMarkMatchingVariants(true);

        if ($SearchDTO->facets === true) {
            // TODO: Add faceted search
            // TODO: Implement the login in addFacets function
            $productSearchRequest->withFacets($this->addFacets($SearchDTO));
        }

        if (!empty($SearchDTO->keyword)) {

            $storeId = $this->getStoreIdByKey($SearchDTO->storeKey);

            // TODO: Add keyword search
            // TODO: Implement the logic in addQuery function
            $productSearchRequest->withQuery($this->addQuery($SearchDTO, $storeId));
        }
        
        return $this->api
            ->products()
            ->search()
            ->post($productSearchRequest->build())
            ->execute();
    }

    private function addFacets(SearchDTO $SearchDTO): ProductSearchFacetExpressionCollection
    {
        return ProductSearchFacetExpressionCollection::of();
    }

    private function addQuery(SearchDTO $SearchDTO, string $storeId): SearchQuery
    {
        return SearchAndExpressionBuilder::of()->build();
    }

    private function getStoreIdByKey(string $storeKey): string
    {
        return $this->api
            ->stores()
            ->withKey($storeKey)
            ->get()
            ->execute()
            ->getId();
    }
}
