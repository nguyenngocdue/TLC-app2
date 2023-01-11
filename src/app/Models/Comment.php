<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Comment extends ModelExtended
{
    protected $fillable = ['content', 'position_rendered', 'owner_id', 'category'];
    protected $table = "comments";
    protected $primaryKey = 'id';
    public $nameless = true;

    public $eloquentParams = [
        "commentable" => ['morphTo', Comment::class, 'commentable_type', 'commentable_id'],
        "user" => ['belongsTo', User::class, 'owner_id'],
        "getCategory" => ['belongsTo', Field::class, 'category'],
    ];

    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getCategory()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function commentable()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }
}
