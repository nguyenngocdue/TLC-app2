<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_07 extends ModelExtended
{
    protected $fillable = ['id', 'content', 'comment_1', 'comment_2', 'comment_3'];
    protected $table = "zunit_test_07s";

    public $eloquentParams = [
        "comments" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "attachment" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public function comments()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }

    public function attachment()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
}
