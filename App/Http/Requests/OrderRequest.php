<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\DTO\OrderDTO;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cartId' => 'required|string',
            'cartVersion' => 'required|integer',
        ];
    }
    public function toDto(): OrderDTO
    {
        return new OrderDTO(
            cartId: $this->input('cartId'),
            cartVersion: $this->input('cartVersion'),
        );
    }
}
