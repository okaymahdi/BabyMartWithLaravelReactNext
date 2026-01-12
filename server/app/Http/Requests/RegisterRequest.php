<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * ✅ Determine if the user is authorized to make this request.
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
     // 📝 Validation rules
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:50',                  // 📝 Name required
            'username' => 'nullable|string|min:2|max:50|unique:users,username', // 🆔 optional username
            'email' => 'required|email|unique:users,email',            // ✉️ Email required
            'password' => 'required|string|min:6|confirmed',           // 🔐 Password + confirmation

            'role' => 'nullable|in:user,admin,manager',                // 👑 Role

            'avatar' => 'nullable|url|max:255',                        // 🖼 Optional avatar
            'gender' => 'nullable|in:male,female,other',               // 🚻 Optional gender

            'addresses' => 'nullable|array',                           // 🏠 Optional addresses array
            'addresses.*.street' => 'required_with:addresses|string',
            'addresses.*.city' => 'required_with:addresses|string',
            'addresses.*.state' => 'required_with:addresses|string',
            'addresses.*.country' => 'required_with:addresses|string',
            'addresses.*.postalCode' => 'required_with:addresses|string',
            'addresses.*.isDefault' => 'boolean',

            'wish_list' => 'nullable|array',                            // ❤️ Optional
            'cart' => 'nullable|array',                                 // 🛒 Optional
        ];
    }


}
