<?php

namespace App\Policies;

use App\Models\ToDo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ToDoPolicy
{
    use HandlesAuthorization;

    public function view(User $user, ToDo $todo)
    {
        return $user->id === $todo->user_id;
    }

    public function update(User $user, ToDo $todo)
    {
        return $user->id === $todo->user_id;
    }

    public function delete(User $user, ToDo $todo)
    {
        return $user->id === $todo->user_id;
    }
}
