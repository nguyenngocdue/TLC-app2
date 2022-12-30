<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Workplace extends ModelExtended
{
    protected $fillable = ["name", "description", "standard_working_hour", "def_assignee", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'workplaces';

    public $eloquentParams = [
        "user" => ['hasMany', User::class, 'workplace'],
        "getAssignee" => ["belongsTo", User::class, 'def_assignee'],
    ];
    public $oracyParams = [
        "getDefMonitors()" => ["getCheckedByField", User::class],
    ];


    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefMonitors()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
