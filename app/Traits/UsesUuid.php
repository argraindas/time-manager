<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait UsesUuid
{
    /**
     * Sets uuid on model create
     */
    protected static function bootUsesUuid()
    {
        static::creating(function (Model $model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
