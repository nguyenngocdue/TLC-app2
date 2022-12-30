<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_discipline extends ModelExtended
{
    protected $fillable = ["name", "description", "def_assignee", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'user_disciplines';

    public $eloquentParams = [
        "user" => ['hasMany', User::class, 'discipline', 'id'],
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
