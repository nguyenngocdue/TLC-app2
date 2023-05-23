<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_discipline_1 extends ModelExtended
{
    public $fillable = ["id", "name", "description", "slug", "def_assignee", "prod_discipline_id", "owner_id"];

    protected $table = 'prod_discipline_1s';
    protected static $statusless = true;

    public $eloquentParams = [
        "getDiscipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],
        "getDefAssignee" => ['belongsTo', User::class, 'def_assignee'],

        "getDiscipline2" => ['hasMany', Prod_discipline_2::class, 'prod_discipline_1_id'],
    ];

    public $oracyParams = [
        "getDefMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getDiscipline2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefAssignee()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDiscipline()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefMonitors1()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
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
