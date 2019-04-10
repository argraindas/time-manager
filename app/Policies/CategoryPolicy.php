<?php

namespace App\Policies;

use App\User;
use App\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the category.
     *
     * @param  User     $user
     * @param  Category $category
     *
     * @return bool
     */
    public function update(User $user, Category $category)
    {
        return $category->user_id == $user->id;
    }
}
