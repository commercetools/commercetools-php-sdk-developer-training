<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\DTO\CustomObjectDTO;
use App\DTO\Subscriber;

class CustomObjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'container' => 'required|string',
            'key' => 'required|string',
            'subscriber' => 'required|array',
            'subscriber.email' => 'required|string|email',
            'subscriber.name' => 'required|string',
        ];
    }

    public function toDto(): CustomObjectDTO
    {
        $subscriberData = $this->input('subscriber');

        $subscriber = new Subscriber(
            email: $subscriberData['email'],
            name: $subscriberData['name'],
        );

        return new CustomObjectDTO(
            container: $this->input('container'),
            key: $this->input('key'),
            subscriber: $subscriber,
        );
    }
}
