<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            'user_id' => 'required',
            'car_id' => 'required',
            'comfort_rating' => 'required|integer|min:1|max:5',
            'driving_experience_rating' => 'required|integer|min:1|max:5',
            'fuel_efficiency_rating' => 'required|integer|min:1|max:5',
            'safety_rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ];
    }
}
