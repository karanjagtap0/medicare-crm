<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'medicine_id' => ['required', 'exists:medicines,id'],
            'medicine_batch_id' => ['required', 'exists:medicine_batches,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
