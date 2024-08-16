<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class It_ticket extends ModelExtended
{
    protected $fillable = [
        "name",
        "description",
        "assignee_1",
        "due_date",
        "priority_id",
        "it_ticket_sub_cat_id",
        "it_ticket_cat_id",
        "owner_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getPriority" => ['belongsTo', Priority::class, 'priority_id'],
        "getCategory" => ['belongsTo', It_ticket_cat::class, 'it_ticket_cat_id'],
        "getSubCategory" => ['belongsTo', It_ticket_sub_cat::class, 'it_ticket_sub_cat_id'],

        "getAssignee1" => ['belongsTo', User::class, 'assignee_1'],
    ];

    public function getPriority()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getCategory()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubCategory()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
