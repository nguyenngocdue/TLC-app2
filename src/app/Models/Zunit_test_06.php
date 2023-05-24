<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_06 extends ModelExtended
{
    protected $fillable = ['id', 'name', 'parent_id', 'order_no', 'owner_id',];
    protected $table = "zunit_test_06s";
    protected static $statusless = true;

    public $eloquentParams = [
        "attachment_1" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "comment_1" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_2" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_3" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_4" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        // "comment_5" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],

        "getDiscipline1" => ['hasMany', Prod_discipline_1::class, 'prod_discipline_id'],
    ];
    public function attachment_1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getDiscipline1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function comment_1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_3()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_4()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_5()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}
