<?php

namespace App\BigThink;

use App\Models\Comment;

trait HasComments
{
    function comments()
    {
        return $this->morphMany(Comment::class, 'commentable', 'commentable_type', 'commentable_id');
    }
}
