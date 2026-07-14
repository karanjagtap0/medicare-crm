<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'customer_code' => ['sometimes', 'required', 'string', 'max:20', 'unique:customers,customer_code,'.$id],
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:customers,email,'.$id],
            'mobile' => ['sometimes', 'required', 'string', 'max:15', 'unique:customers,mobile,'.$id],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:Male,Female,Other'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
