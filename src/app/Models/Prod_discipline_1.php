<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_discipline_1 extends ModelExtended
{
    public $fillable = ["id", "name", "description", "slug", "def_assignee", "prod_discipline_id", "owner_id"];

    public static $statusless = true;

    public static $eloquentParams = [
        "getDiscipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],
        "getDefAssignee" => ['belongsTo', User::class, 'def_assignee'],

        "getDiscipline2" => ['hasMany', Prod_discipline_2::class, 'prod_discipline_1_id'],

        "getDefMonitors1" => ['belongsToMany', User::class, 'ym2m_prod_discipline_1_user_def_monitor_1'],
    ];

    public function getDiscipline2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefAssignee()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefMonitors1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id'],
            ['dataIndex' => 'prod_discipline_id'],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'slug'],
            ['dataIndex' => 'def_assignee'],
        ];
    }
}
