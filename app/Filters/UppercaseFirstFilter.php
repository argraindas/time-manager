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
        return is_string($value) ? ucfirst(strtolower($value)) : $value;
    }
}
