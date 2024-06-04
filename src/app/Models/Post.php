<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Post extends ModelExtended
{
    protected $fillable = ["content", "owner_id"];

    public static $eloquentParams = [
        "post_attachments" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "post_comments" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        // "post_likes" => ['belongsToMany', Like::class, 'likes', 'post_id', 'user_id'],
    ];
}
