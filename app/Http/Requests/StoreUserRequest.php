<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|alpha|max:255',
            'surname' => 'required|alpha|max:255',
            'sex' => 'required',
            'image' => 'nullable|image',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|numeric|digits:9',
            'license' => 'required',
            'birth' => 'required|date',
            'town' => 'required|alpha|max:255',
            'zip_code' => 'required',
            'country' => 'required|alpha|max:255',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $validatedData = $validator->validated();
            $validatedData['password'] = Hash::make($validatedData['password']);
            $validator->setData($validatedData);
        });
    }
}
