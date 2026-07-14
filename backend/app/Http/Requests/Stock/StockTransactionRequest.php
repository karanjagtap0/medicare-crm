<?php

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StockTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'medicine_id' => 'required|integer|exists:medicines,id',
            'medicine_batch_id' => 'required|integer|exists:medicine_batches,id',
            'quantity' => 'required|integer|not_in:0',
            'reference_no' => 'nullable|string|max:255',
            'remarks' => 'nullable|string'
        ];
    }
}
