<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_task extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'status'];

    protected static $statusless = true;

    public $eloquentParams = [
        'getDiscipline' => ['belongsTo', User_discipline::class, 'discipline_id'],
    ];

    public function getDiscipline()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
