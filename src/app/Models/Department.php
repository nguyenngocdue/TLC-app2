<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Department extends ModelExtended
{
    protected $fillable = [
        "id", "owner_id", "name", "description",
        "head_of_department", "hide_in_org_chart", "hide_in_survey",
        "slug", "order_no", "parent_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getHOD" => ['belongsTo', User::class, 'head_of_department'],
        "getMembers" => ['hasMany', User::class, 'department'],
        "getMembers2" => ['hasMany', User::class, 'department'],
    ];

    public function getHOD()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMembers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMembers2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'hide_in_org_chart'],
        ];
    }
}
