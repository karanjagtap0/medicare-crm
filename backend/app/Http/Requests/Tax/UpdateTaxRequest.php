<?php

namespace App\Http\Requests\Tax;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaxRequest extends FormRequest
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
        $id = $this->route('id') ?? $this->route('tax');
        
        return [
            'name' => 'nullable|string|max:100',
            'code' => 'nullable|string|max:30|unique:taxes,code,' . $id,
            'type' => 'nullable|in:GST,CGST_SGST,IGST,VAT,CESS',
            'rate' => 'nullable|numeric|min:0|max:999.99',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ];
    }
}
