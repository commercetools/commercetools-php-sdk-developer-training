<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\DTO\CartDTO;

class CartCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country' => 'required|string',
            'quantity' => 'nullable|integer',
            'sku' => 'required|string',
            'currency' => 'required|string',
        ];
    }
    public function toDto(): CartDTO
    {
        return new CartDTO(
            country: $this->input('country'),
            quantity: $this->input('quantity'),
            sku: $this->input('sku'),
            currency: $this->input('currency'),
        );
    }

}
