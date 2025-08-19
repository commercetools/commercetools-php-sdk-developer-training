<?php

namespace App\DTO;

class Subscriber {
    public function __construct(
        public readonly string $email,
        public readonly string $name,
    ) {}
}

