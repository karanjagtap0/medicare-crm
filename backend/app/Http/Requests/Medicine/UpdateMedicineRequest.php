<?php

namespace App\Http\Requests\Medicine;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicineRequest extends FormRequest
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
        $id = $this->route('id') ?? $this->route('medicine');
        
        return [
            'medicine_code' => 'nullable|string|max:30|unique:medicines,medicine_code,' . $id,
            'sku' => 'nullable|string|max:50|unique:medicines,sku,' . $id,
            'name' => 'nullable|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'tax_id' => 'nullable|integer|exists:taxes,id',
            'unit_of_measure_id' => 'nullable|integer|exists:unit_of_measures,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'minimum_stock' => 'nullable|integer|min:0',
            'maximum_stock' => 'nullable|integer|min:0',
            'prescription_required' => 'boolean',
            'status' => 'boolean',
        ];
    }
}
