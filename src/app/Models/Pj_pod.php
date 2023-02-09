<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_pod extends ModelExtended
{
    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "slug", "pj_module_id"];
    protected $primaryKey = 'id';
    protected $table = 'pj_pods';

    public $eloquentParams = [
        'getPjModule' => ['belongsTo', Pj_module::class, 'pj_module_id'],
    ];

    public function getPjModule()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
