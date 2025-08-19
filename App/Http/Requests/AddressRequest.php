<?php

namespace App\Http\Requests;

use Commercetools\Api\Models\Common\AddressModel;
use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstName' => 'nullable|string',
            'lastName' => 'nullable|string',
            'streetNumber' => 'nullable|string',
            'streetName' => 'nullable|string',
            'city' => 'nullable|string',
            'region' => 'nullable|string',
            'country' => 'required|string',
            'email' => 'required|string',
        ];
    }
    public function toAddress(): AddressModel
    {
        return new AddressModel(
            firstName: $this->input('firstName'),
            lastName: $this->input('lastName'),
            streetNumber: $this->input('streetNumber'),
            streetName: $this->input('streetName'),
            city: $this->input('city'),
            region: $this->input('region'),
            country: $this->input('country'),
            email: $this->input('email'),
        );
    }

}
