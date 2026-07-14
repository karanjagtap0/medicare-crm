<?php

namespace App\Http\Requests\Medicine;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicineBatchRequest extends FormRequest
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
        $id = $this->route('id') ?? $this->route('medicine_batch');
        
        return [
            'medicine_id' => 'nullable|integer|exists:medicines,id',
            'batch_number' => 'nullable|string|max:50|unique:medicine_batches,batch_number,' . $id,
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:manufacturing_date',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'quantity_received' => 'nullable|integer|min:1',
            'available_quantity' => 'nullable|integer|min:0',
            'barcode' => 'nullable|string|max:255',
            'status' => 'nullable|in:Active,Expired,Blocked',
        ];
    }
}
