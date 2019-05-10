<?php

namespace App\Policies;

use App\User;
use App\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the task.
     *
     * @param  User   $user
     * @param  Task   $task
     *
     * @return bool
     */
    public function update(User $user, Task $task)
    {
        return $task->creator_id == $user->id;
    }

}
