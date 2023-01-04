<?php

namespace App\Http\Traits;

use App\Models\Comment;

trait HasComments
{
    function comments()
    {
        return $this->morphMany(Comment::class, 'commentable', 'commentable_type', 'commentable_id');
    }
}
