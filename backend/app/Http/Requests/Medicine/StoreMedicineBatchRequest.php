<?php

namespace App\Http\Requests\Medicine;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMedicineBatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'medicine_id' => 'required|integer|exists:medicines,id',
            'batch_number' => 'required|string|max:50|unique:medicine_batches,batch_number',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'required|date|after_or_equal:manufacturing_date',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity_received' => 'required|integer|min:1',
            'available_quantity' => 'nullable|integer|min:0',
            'barcode' => 'nullable|string|max:255',
            'status' => 'nullable|in:Active,Expired,Blocked',
        ];
    }
}
