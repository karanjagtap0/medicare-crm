<?php

namespace App\Http\Requests\Medicine;

use Illuminate\Foundation\Http\FormRequest;

class ReorderMedicineImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'orders' => 'required|array',
            'orders.*.id' => 'required|integer|exists:medicine_images,id',
            'orders.*.sort_order' => 'required|integer'
        ];
    }
}
