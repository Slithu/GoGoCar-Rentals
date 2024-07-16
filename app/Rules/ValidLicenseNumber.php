<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class ValidLicenseNumber implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Sprawdzenie, czy wartość pasuje do wzoru XXXXX/XX/XXXX
        return preg_match('/^\d{5}\/\d{2}\/\d{4}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be in the format XXXXX/XX/XXXX.';
    }
}
