<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the comment.
     * @param \App\Models\User $user
     * @param \App\Models\Comment $comment
     * @return mixed
     */
    public function update(User $user, Comment $comment):bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can delete the comment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Comment  $comment
     * @return mixed
     */
    public function delete(User $user, Comment $comment):bool
    {
        return $user->id === $comment->user_id||$user->is_admin;
    }
}
