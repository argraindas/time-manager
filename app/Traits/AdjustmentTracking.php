<?php

namespace App\Traits;

use App\User;
use Illuminate\Database\Eloquent\Model;

trait AdjustmentTracking
{
    /**
     * Boot the trait.
     */
    protected static function bootAdjustmentTracking()
    {
        static::updating(function(Model $model) {
            $model->adjust();
        });
    }

    /**
     * Save model changes
     *
     * @param null $userId
     * @param null $newValues
     */
    public function adjust($userId = null, $newValues = null)
    {
        $userId = $userId ?: auth()->id();
        $diff = $this->getDiff($newValues);

        $this->adjustments()->attach($userId, $diff);
    }

    /**
     * Model knows all its changes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function adjustments()
    {
        return $this->morphToMany(User::class, 'adjustment')
            ->as('changes')
            ->withPivot(['id', 'before', 'after'])
            ->withTimestamps()
            ->latest('pivot_id');
    }

    /**
     * Gets before and after changes array
     *
     * @param $newValues
     *
     * @return array
     */
    protected function getDiff($newValues = null)
    {
        $changed = $newValues ?: $this->getDirty();

        $before = json_encode(array_intersect_key($this->fresh()->toArray(), $changed));
        $after = json_encode($changed);

        return compact('before', 'after');
    }
}
