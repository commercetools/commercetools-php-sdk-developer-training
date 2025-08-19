<?php

namespace App\DTO;

use Commercetools\Api\Models\Common\AddressModel;

class CustomerDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public ?string $key,
        public ?string $firstName,
        public ?string $lastName,
        public ?AddressModel $address,
        public ?bool $isDefaultShippingAddress,
        public ?bool $isDefaultBillingAddress,
        public ?string $anonymousCartId,
    ) {
    }
}
