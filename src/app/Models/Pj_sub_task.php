<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_sub_task extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'status', 'owner_id'];

    protected static $statusless = true;

    public $oracyParams = [
        "getParentTasks()" => ["getCheckedByField", Pj_task::class],
    ];

    public function getParentTasks()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
