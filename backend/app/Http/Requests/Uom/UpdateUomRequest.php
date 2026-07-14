<?php

namespace App\Http\Requests\Uom;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUomRequest extends FormRequest
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
        $id = $this->route('id') ?? $this->route('uom');
        
        return [
            'name' => 'nullable|string|max:100',
            'code' => 'nullable|string|max:20|unique:unit_of_measures,code,' . $id,
            'symbol' => 'nullable|string|max:20',
            'type' => 'nullable|in:Quantity,Weight,Volume,Length',
            'status' => 'boolean',
        ];
    }
}
