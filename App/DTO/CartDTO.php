<?php

namespace App\DTO;

class CartDTO
{
    public function __construct(
        public readonly string $country,
        public readonly ?int $quantity,
        public readonly string $sku,
        public readonly string $currency,
    ) {}
}
