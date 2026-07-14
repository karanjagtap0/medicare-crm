<?php

namespace App\Http\Requests\Medicine;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMedicineRequest extends FormRequest
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
            'medicine_code' => 'nullable|string|max:30|unique:medicines,medicine_code',
            'sku' => 'nullable|string|max:50|unique:medicines,sku',
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'tax_id' => 'required|integer|exists:taxes,id',
            'unit_of_measure_id' => 'required|integer|exists:unit_of_measures,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'minimum_stock' => 'nullable|integer|min:0',
            'maximum_stock' => 'nullable|integer|min:0',
            'prescription_required' => 'boolean',
            'status' => 'boolean',
        ];
    }
}
