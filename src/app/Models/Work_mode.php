<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Work_mode extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'work_modes';

    public $eloquentParams = [
        // "getHROTRLines" => ["hasMany", Hr_overtime_request_line::class, 'work_mode_id'],
    ];

    // public function getHROTRLines()
    // {
    //     $p = $this->eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }
}
