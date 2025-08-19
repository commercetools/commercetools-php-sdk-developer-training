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
        // if ($SearchDTO->facets === true) {
        //     $productSearchRequest->withFacets($this->addFacets($SearchDTO));
        // }
        if (!empty($SearchDTO->keyword)) {
            $storeId = $this->api
                ->stores()
                ->withKey($SearchDTO->storeKey)
                ->get()
                ->execute()
                ->getId();
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
        return ProductSearchFacetExpressionCollection::of()
            ->add(ProductSearchFacetDistinctExpressionBuilder::of()
                ->withDistinct(ProductSearchFacetDistinctValueBuilder::of()
                    ->withField("variants.attributes.search-color.label")
                    ->withName("Color")
                    ->withFieldType("lenum")
                    ->withLanguage($SearchDTO->locale)
                    ->withLevel("variants")
                    ->withScope("all")
                    ->build()))
            ->add(ProductSearchFacetDistinctExpressionBuilder::of()
                ->withDistinct(ProductSearchFacetDistinctValueBuilder::of()
                    ->withField("variants.attributes.search-finish.label")
                    ->withName("Finish")
                    ->withFieldType("lenum")
                    ->withLanguage($$SearchDTO->locale)
                    ->withLevel("variants")
                    ->withScope("all")
                    ->build()));
    }

    private function addQuery(SearchDTO $SearchDTO, string $storeId): SearchQuery
    {
        return SearchExactExpressionBuilder::of()
                ->withExact(SearchExactValueBuilder::of()
                    ->withField("stores")
                    ->withValue($storeId)
                    ->withFieldType("set_reference")
                    ->build())
                ->build();
        // return SearchAndExpressionBuilder::of()
        //     ->withAnd(
        //         SearchQueryCollection::of()
        //                 ->add(SearchFullTextExpressionBuilder::of()
        //                     ->withFullText(SearchFullTextValueBuilder::of()
        //                         ->withField("name")
        //                         ->withLanguage($SearchDTO->locale)
        //                         ->withValue($SearchDTO->keyword)
        //                         ->withMustMatch("all")
        //                         ->build()))
        //                 ->add(SearchExactExpressionBuilder::of()
        //                     ->withExact(SearchExactValueBuilder::of()
        //                         ->withField("stores")
        //                         ->withValue($storeId)
        //                         ->withFieldType("set_reference")
        //                         ->build()))
        //     )->build();
    }
}
