<?php

namespace App\DTO;

class OrderCustomFieldsDTO
{
    public function __construct(
        public readonly string $time,
        public readonly string $instructions,
    ) {}
}
