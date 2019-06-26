<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait UsesUuid
{
    protected static function bootUsesUuid()
    {
        static::creating(function (Model $model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
