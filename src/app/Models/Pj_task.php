<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_task extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'status', 'owner_id'];

    protected static $statusless = true;

    public $eloquentParams = [];

    public $oracyParams = [
        "getDisciplines()" => ["getCheckedByField", User_discipline::class],
        "getLods()" => ["getCheckedByField", Term::class],
    ];

    public function getDisciplines()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getLods()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
