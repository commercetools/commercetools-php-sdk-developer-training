<?php

namespace App\DTO;

class OrderDTO
{
    public function __construct(
        public readonly string $cartId,
        public readonly string $cartVersion,
    ) {}
}
