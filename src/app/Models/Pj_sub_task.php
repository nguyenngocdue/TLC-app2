<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_sub_task extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'status'];

    protected static $statusless = true;

    public $eloquentParams = [
        'getTask' => ['belongsTo', Pj_task::class, 'task_id'],
    ];

    public function getTask()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
