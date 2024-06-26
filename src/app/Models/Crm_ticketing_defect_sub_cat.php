<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Crm_ticketing_defect_sub_cat extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'owner_id', 'status',
        "defect_cat_id",
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        'getDefectCat' => ['belongsTo', Crm_ticketing_defect_cat::class, 'defect_cat_id'],
    ];

    public function getDefectCat()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
