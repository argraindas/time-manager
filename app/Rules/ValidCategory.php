<?php

namespace App\Rules;

use App\Category;
use Illuminate\Contracts\Validation\Rule;

class ValidCategory implements Rule
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
        return Category::where([
            'id' => $value,
            'user_id' => auth()->user()->id,
        ])->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Category is not valid!';
    }
}
