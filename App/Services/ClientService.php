<?php

namespace App\Services;

use Commercetools\Api\Client\ClientCredentialsConfig;
use Commercetools\Api\Client\Config;
use Commercetools\Api\Client\Resource\ResourceByProjectKey;
use Commercetools\Client\ApiRequestBuilder;
use Commercetools\Client\ClientCredentials;
use Commercetools\Client\ClientFactory;
use Commercetools\Import\Client\ImportRequestBuilder;
use Illuminate\Support\Facades\Log;

class ClientService
{
    private ?ApiRequestBuilder $apiClient = null;
    private ?ImportRequestBuilder $importClient = null;

    public function getApiClient(): ResourceByProjectKey
    {
        if (!$this->apiClient) {
            $client = ClientFactory::of()->createGuzzleClient(
                new Config(),
                new ClientCredentialsConfig(new ClientCredentials(
                    config('services.commercetools.client_id'),
                config('services.commercetools.client_secret')
                )),
                Log::getLogger()
            );

            $this->apiClient = new ApiRequestBuilder(
                config('services.commercetools.project_key'),
                $client
            );
        }

        return $this->apiClient->with();
    }

    public function getImportClient(): \Commercetools\Import\Client\Resource\ResourceByProjectKey
    {
        if (!$this->importClient) {
            $importClient = ClientFactory::of()->createGuzzleClient(
                new \Commercetools\Import\Client\Config(),
                new ClientCredentialsConfig(new ClientCredentials(
                    config('services.commercetools.client_id'),
                    config('services.commercetools.client_secret')
                ))
            );

            $this->importClient = new ImportRequestBuilder(
                $importClient
            );
        }

        return $this->importClient->withProjectKeyValue(config('services.commercetools.project_key'));
    }

}
