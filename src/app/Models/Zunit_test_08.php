<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_08 extends ModelExtended
{
    protected $fillable = [
        'id',
        'name',
        'description',
        'status',

        'owner_id',
        'parent_id',

        'assignee_1',
        'assignee_2',
        "order_no",
        'closed_at',
    ];
    protected $table = "zunit_test_08s";

    public $eloquentParams = [
        "comment_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "getAssignee1" => ["belongsTo", User::class, 'assignee_1'],
        "getAssignee2" => ["belongsTo", User::class, 'assignee_2'],
    ];

    public function comment_rejected_reason()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getAssignee1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssignee2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
