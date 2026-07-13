<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:191'],
            'dob' => ['nullable', 'date'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'mobile_no' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:191'],
            'city' => ['nullable', 'string', 'max:191'],
            'state' => ['nullable', 'string', 'max:191'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'weight' => ['nullable', 'numeric', 'between:1,999.99'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a valid string.',
            'name.max' => 'Name may not be greater than 191 characters.',

            'dob.date' => 'Please enter a valid date of birth.',

            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email may not be greater than 191 characters.',
            'email.unique' => 'This email is already registered.',

            'mobile_no.max' => 'Mobile number may not be greater than 20 characters.',

            'address.max' => 'Address may not be greater than 191 characters.',

            'city.max' => 'City may not be greater than 191 characters.',

            'state.max' => 'State may not be greater than 191 characters.',

            'zip_code.max' => 'Zip code may not be greater than 20 characters.',

            'weight.numeric' => 'Weight must be a valid number.',
            'weight.between' => 'Weight must be between 1 and 999.99.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',

            'status.required' => 'Status is required.',
            'status.boolean' => 'Status must be either active or inactive.',
        ];
    }
}
