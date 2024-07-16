<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
            'brand' => 'required|max:255',
            'model' => 'required|max:255',
            'car_body' => 'required',
            'engine_type' => 'required',
            'transmission' => 'required',
            'engine_power' => 'required|numeric',
            'seats' => 'required|numeric',
            'doors' => 'required|numeric',
            'suitcases' => 'required|numeric',
            'price' => 'required|numeric|between:0,9999.99',
            'description' => 'required|max:1500',
            'image' => 'nullable|image'
        ];
    }
}
