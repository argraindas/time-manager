<?php

namespace App\Filters;

use Waavi\Sanitizer\Contracts\Filter;

class UppercaseFirstFilter implements Filter
{
    /**
     *  Uppercase first letter of the given string.
     *
     *  @param  string  $value
     *  @return string
     */
    public function apply($value, $options = [])
    {
        if (! in_array('noLower', $options)) {
            $value = strtolower($value);
        }

        return is_string($value) ? ucfirst($value) : $value;
    }
}
