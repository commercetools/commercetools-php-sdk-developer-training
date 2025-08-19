<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\DTO\SearchDTO;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'locale' => 'required|string',
            'keyword' => 'nullable|string',
            'storeKey' => 'required|string',
            'facets' => 'nullable',
            'country' => 'required|string|size:2',
            'currency' => 'required|string|size:3',
        ];
    }
    public function toDto(): SearchDTO
    {
        return new SearchDTO(
            locale: $this->input('locale'),
            keyword: $this->input('keyword'),
            storeKey: $this->input('storeKey'),
            facets: $this->boolean('facets'),
            country: $this->input('country'),
            currency: $this->input('currency'),
        );
    }

}
