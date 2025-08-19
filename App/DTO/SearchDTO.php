<?php

namespace App\DTO;

class SearchDTO
{
    public function __construct(
        public readonly string $locale,
        public readonly ?string $keyword,
        public readonly string $storeKey,
        public readonly ?bool $facets,
        public readonly string $country,
        public readonly string $currency,
    ) {}
}
