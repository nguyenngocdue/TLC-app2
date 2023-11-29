<?php

namespace App\Models;

use App\BigThink\HasProperties;
use App\BigThink\ModelExtended;

class Comment extends ModelExtended
{
    use HasProperties;

    protected $fillable = ['id', 'content', 'position_rendered', 'owner_id', 'category', 'commentable_type', 'commentable_id'];

    public static $nameless = true;
    public static $statusless = true;

    public static $eloquentParams = [
        "getCategory" => ['belongsTo', Field::class, 'category'],
        "comment_attachment" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],

        "commentable" => ['morphTo', Comment::class, 'commentable_type', 'commentable_id'],
    ];

    public function comment_attachment()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getCategory()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function commentable()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }
}
