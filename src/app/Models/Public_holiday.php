<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Public_holiday extends ModelExtended
{
    protected $fillable = ['id', 'name', 'years', 'workplace_id', 'ph_date', 'ph_hours'];
    protected $table = "public_holidays";

    public $eloquentParams = [
        "getWorkplace" => ['belongsTo', Workplace::class, 'workplace_id'],
    ];

    public function getWorkplace()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
