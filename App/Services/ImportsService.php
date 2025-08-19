<?php

namespace App\Services;

use Commercetools\Exception\ApiClientException;
use Commercetools\Import\Client\Resource\ResourceByProjectKey;
use Commercetools\Import\Models\Common\AssetDimensionsBuilder;
use Commercetools\Import\Models\Common\ImageBuilder;
use Commercetools\Import\Models\Common\ImageCollection;
use Commercetools\Import\Models\Common\LocalizedStringBuilder;
use Commercetools\Import\Models\Common\MoneyBuilder;
use Commercetools\Import\Models\Common\ProductTypeKeyReferenceBuilder;
use Commercetools\Import\Models\Importrequests\ImportResponse;
use Commercetools\Import\Models\Importrequests\ProductDraftImportRequestBuilder;
use Commercetools\Import\Models\Importsummaries\ImportSummary;
use Commercetools\Import\Models\Productdrafts\PriceDraftImportBuilder;
use Commercetools\Import\Models\Productdrafts\PriceDraftImportCollection;
use Commercetools\Import\Models\Productdrafts\ProductDraftImportBuilder;
use Commercetools\Import\Models\Productdrafts\ProductDraftImportCollection;
use Commercetools\Import\Models\Productdrafts\ProductVariantDraftImportBuilder;
use Illuminate\Http\UploadedFile;

class ImportsService
{
    private ResourceByProjectKey $importApi;

    private string $containerKey = 'products-import-container';

    public function __construct(private ClientService $clientService)
    {
        $this->importApi = $this->clientService->getImportClient();
    }

    /**
     * @param string $containerKey
     * @return ImportSummary
     * @throws ApiClientException
     */
    public function getImportSummary(): ImportSummary
    {
        return $this->importApi
            ->importContainers()
            ->withImportContainerKeyValue($this->containerKey)
            ->importSummaries()
            ->get()
            ->execute();
    }

    /**
     * @param UploadedFile $file
     * @return ImportResponse
     * @throws ApiClientException
     */
    public function importProducts(UploadedFile $file): ImportResponse
    {
        return $this->importApi
            ->productDrafts()
            ->importContainers()
            ->withImportContainerKeyValue($this->containerKey)
            ->post(ProductDraftImportRequestBuilder::of()
                ->withResources($this->createImportProductDraftCollection($file))
                ->build())
            ->execute();
    }

    /**
     * @param UploadedFile $csvFile
     * @return ProductDraftImportCollection
     * @throws ApiClientException
    */
    public function createImportProductDraftCollection(UploadedFile $csvFile): ProductDraftImportCollection
    {
        $participantNamePrefix = 'ndd-';
        $productDataArray = array_slice($this->readDataFromCSV($csvFile), 1);

        $productCollection = ProductDraftImportCollection::of();

        foreach ($productDataArray as $row) {
            if (count($row) < 7) {
                continue;
            }

            $imageUrl = $row[6] ?? '';
            $images = ImageCollection::of();

            if (!empty($imageUrl)) {
                $images->add(
                    ImageBuilder::of()
                        ->withUrl($imageUrl)
                        ->withDimensions(
                            AssetDimensionsBuilder::of()->withW(177)->withH(237)->build()
                        )
                        ->build()
                );
            }

            $productCollection->add(
                ProductDraftImportBuilder::of()
                    ->withProductType(
                        ProductTypeKeyReferenceBuilder::of()->withKey($row[0])->build()
                    )
                    ->withKey($participantNamePrefix . $row[2])
                    ->withName(
                        LocalizedStringBuilder::of()->put('en', $participantNamePrefix . $row[2])->build()
                    )
                    ->withSlug(
                        LocalizedStringBuilder::of()->put('en', $participantNamePrefix . $row[2])->build()
                    )
                    ->withDescription(
                        LocalizedStringBuilder::of()->put('en', $participantNamePrefix . $row[3])->build()
                    )
                    ->withMasterVariant(
                        ProductVariantDraftImportBuilder::of()
                            ->withSku($participantNamePrefix . $row[1])
                            ->withKey($participantNamePrefix . $row[1])
                            ->withPrices(
                                PriceDraftImportCollection::of()->add(
                                    PriceDraftImportBuilder::of()
                                            ->withValue(

                                            MoneyBuilder::of()
                                                ->withCentAmount((int)((float) $row[4] * 100))
                                                ->withCurrencyCode($row[5])
                                                ->build()
                                            )
                                            ->withKey($participantNamePrefix . $row[5] . $row[1])
                                        ->build()
                                )
                            )
                            ->withImages($images)
                            ->build()
                    )
                    ->build()
            );
        }

        return $productCollection;
    }

    public function readDataFromCSV(string $file): array
    {
        $dataArray = [];

        if (($handle = fopen($file, 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                $dataArray[] = $row;
            }
            fclose($handle);
        }

        return $dataArray;
    }


}
