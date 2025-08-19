<?php

namespace App\Services;

use App\Services\ClientService;
use Commercetools\Api\Client\Resource\ResourceByProjectKey;
use Commercetools\Api\Models\Store\StorePagedQueryResponse;
use commercetools\Exception\ApiClientException;

class ProjectService
{
    private ResourceByProjectKey $api;

    public function __construct(private ClientService $clientService)
    {
        $this->api = $this->clientService->getApiClient();
    }

    /**
     * @return array<string>
     * @throws ApiClientException
     */
    public function getCountries(): array
    {
        $project = $this->api
            ->get()
            ->execute();

        return $project->getCountries();
    }

    /**
     * @return StorePagedQueryResponse
     * @throws ApiClientException
     */
    public function getStores(): StorePagedQueryResponse
    {
        return $this->api
            ->stores()
            ->get()
            ->execute();
    }

}
