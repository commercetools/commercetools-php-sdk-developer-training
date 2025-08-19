<?php

namespace App\Services;

use App\DTO\CustomObjectDTO;
use App\Http\Requests\CustomObjectRequest;
use App\Services\ClientService;
use Commercetools\Api\Client\Resource\ResourceByProjectKey;
use Commercetools\Api\Models\Common\LocalizedStringBuilder;
use Commercetools\Api\Models\CustomObject\CustomObject;
use Commercetools\Api\Models\CustomObject\CustomObjectDraftBuilder;
use Commercetools\Api\Models\Type\CustomFieldStringTypeBuilder;
use Commercetools\Api\Models\Type\FieldDefinitionBuilder;
use Commercetools\Api\Models\Type\FieldDefinitionCollection;
use Commercetools\Api\Models\Type\Type;
use Commercetools\Api\Models\Type\TypeDraftBuilder;
use commercetools\Exception\ApiClientException;

class ExtensionsService
{
    private ResourceByProjectKey $api;

    public function __construct(private ClientService $clientService)
    {
        $this->api = $this->clientService->getApiClient();
    }

    /**
     * @return Type
     * @throws ApiClientException
     */
    public function createType(): Type
    {
        $localizedName = LocalizedStringBuilder::of()
            ->put('en-US', 'Delivery Instructions')
            ->put('de-DE', 'Delivery Instructions')
            ->build();

        $fieldDefinitions = FieldDefinitionCollection::of()
            ->add(FieldDefinitionBuilder::of()
                ->withName('instructions')
                ->withLabel(
                    LocalizedStringBuilder::of()
                        ->put('en-US', 'Instructions')
                        ->put('de-DE', 'Instructions')
                        ->build()
                )
                ->withRequired(true)
                ->withType(CustomFieldStringTypeBuilder::of()->build())
                ->build())
            ->add(FieldDefinitionBuilder::of()
                ->withName('time')
                ->withLabel(
                    LocalizedStringBuilder::of()
                        ->put('en-US', 'Time')
                        ->put('de-DE', 'Time')
                        ->build()
                )
                ->withRequired(true)
                ->withType(CustomFieldStringTypeBuilder::of()->build())
                ->build());

        $draft = TypeDraftBuilder::of()
            ->withKey('delivery-instructions11')
            ->withName($localizedName)
            ->withResourceTypeIds([
                'customer',
                'order'
            ])
            ->withFieldDefinitions($fieldDefinitions)
            ->build();

        return $this->api
            ->types()
            ->post($draft)
            ->execute();
    }

    /**
     * @param string $container
     * @param string $key
     * @return CustomObject
     * @throws ApiClientException
     */
    public function getCustomObjectByContainerAndKey(string $container, string $key): CustomObject
    {
        return $this->api
            ->customObjects()
            ->withContainerAndKey($container, $key)
            ->get()
            ->execute();
    }

    /**

     * @param CustomObjectRequest $customObjectRequest
     * @return CustomObject
     * @throws ApiClientException
     */
    public function createCustomObject(CustomObjectDTO $dto): CustomObject
    {

        $existingValues = [];

        try {
            $existing = $this->getCustomObjectByContainerAndKey($dto->container, $dto->key);

            $rawValue = $existing->getValue();

            $existingValues = is_string($rawValue)
                ? json_decode($rawValue, true)
                : (array) $rawValue;

            if (!is_array($existingValues)) {
                $existingValues = [];
            }
        } catch (ApiClientException $e) {
            if ($e->getCode() !== 404) {
                throw $e;
            }

            $existingValues = [];
        }

        $newEntry = [
            $dto->subscriber->name => $dto->subscriber->email,
        ];

        $existingValues[] = $newEntry;

        $draft = CustomObjectDraftBuilder::of()
            ->withContainer($dto->container)
            ->withKey($dto->key)
            ->withValue($existingValues)
            ->build();

        return $this->api
            ->customObjects()
            ->post($draft)
            ->execute();
    }

}
