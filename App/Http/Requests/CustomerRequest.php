<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\DTO\CustomerDTO;
use Commercetools\Api\Models\Common\AddressModel;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
            'key' => 'nullable|string',
            'firstName' => 'nullable|string',
            'lastName' => 'nullable|string',
            'streetNumber' => 'nullable|string',
            'streetName' => 'nullable|string',
            'city' => 'nullable|string',
            'region' => 'nullable|string',
            'country' => 'nullable|string|size:2',
            'isDefaultShippingAddress' => 'nullable',
            'isDefaultBillingAddress' => 'nullable',
            'anonymousCartId' => 'nullable|string',
        ];
    }

    public function toDto(): CustomerDTO
    {
        return new CustomerDTO(
            email: $this->input('email'),
            password: $this->input('password'),
            key: $this->input('key'),
            firstName: $this->input('firstName'),
            lastName: $this->input('lastName'),
            address: new AddressModel(
                firstName: $this->input('firstName'),
                lastName: $this->input('lastName'),
                streetNumber: $this->input('streetNumber'),
                streetName: $this->input('streetName'),
                city: $this->input('city'),
                region: $this->input('region'),
                country: $this->input('country'),
                email: $this->input('email'),
            ),
            isDefaultShippingAddress: $this->boolean('isDefaultShippingAddress'),
            isDefaultBillingAddress: $this->boolean('isDefaultBillingAddress'),
            anonymousCartId: $this->input('anonymousCartId'),
        );
    }
}
