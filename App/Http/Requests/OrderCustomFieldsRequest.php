<?php

namespace App\Http\Requests;

use App\DTO\OrderCustomFieldsDTO;
use Illuminate\Foundation\Http\FormRequest;

class OrderCustomFieldsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'time' => 'required|string',
            'instructions' => 'required|string',
        ];
    }
    public function toDto(): OrderCustomFieldsDTO
    {
        return new OrderCustomFieldsDTO(
            time: $this->input('time'),
            instructions: $this->input('instructions'),
        );
    }
}
