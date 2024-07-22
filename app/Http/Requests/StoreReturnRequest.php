<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnRequest extends FormRequest
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
            'reservation_id' => 'required',
            'user_id' => 'required',
            'return_date' => 'required',
            'exterior_condition' => 'required',
            'interior_condition' => 'required',
            'exterior_damage_description' => 'nullable|string|max:255',
            'interior_condition_description' => 'nullable|string|max:255',
            'car_parts_condition' => 'nullable|string|max:255',
            'penalty_amount' => 'nullable|numeric|min:0',
            'comments' => 'nullable|string|max:255',
        ];
    }
}
