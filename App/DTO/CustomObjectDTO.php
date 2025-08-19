<?php

namespace App\DTO;

use App\DTO\Subscriber;

class CustomObjectDTO
{
    public function __construct(
        public readonly string $container,
        public readonly string $key,
        public readonly Subscriber $subscriber,
    ) {}
}


