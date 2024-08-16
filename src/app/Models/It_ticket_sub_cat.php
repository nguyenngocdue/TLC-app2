<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class It_ticket_sub_cat extends ModelExtended
{
    protected $fillable = [
        "name",
        "description",
        "def_assignee",
        "it_ticket_cat_id",
        "owner_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getDefAssignee" => ['belongsTo', User::class, 'def_assignee'],
        "getCategory" => ['belongsTo', It_ticket_cat::class, 'it_ticket_cat_id'],
    ];

    public function getDefAssignee()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getCategory()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
