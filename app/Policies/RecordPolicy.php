<?php

namespace App\Policies;

use App\User;
use App\Record;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecordPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the record.
     *
     * @param  User   $user
     * @param  Record $record
     *
     * @return bool
     */
    public function update(User $user, Record $record)
    {
        return $record->user_id === $user->id;
    }

}
