<?php

namespace App\Http\Requests;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [
        'name' => 'required|string|min:2|max:50',
        'username' => 'required|string|min:2|max:50|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'role' => 'nullable|in:user,admin,manager',
        'gender' => 'nullable|in:male,female,other',
        'addresses' => 'nullable|array',
        'addresses.*.street' => 'required_with:addresses|string',
        'addresses.*.city' => 'required_with:addresses|string',
        'addresses.*.state' => 'required_with:addresses|string',
        'addresses.*.country' => 'required_with:addresses|string',
        'addresses.*.postalCode' => 'required_with:addresses|string',
        'addresses.*.isDefault' => 'boolean',
    ];
}

}
