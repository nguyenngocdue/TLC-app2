<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_staircase extends ModelExtended
{
    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "slug", "pj_building_id", "pj_level_id", "pj_module_type_id",
     "pj_name_id", "pj_character_id"];
    protected $primaryKey = 'id';
    protected $table = 'pj_staircases';

    public $eloquentParams = [
        'getPjBuilding' => ['belongsTo', Term::class, 'pj_building_id'],
        'getPjLevel' => ['belongsTo', Term::class, 'pj_level_id'],
        'getPjType' => ['belongsTo', Term::class, 'pj_module_type_id'],
        'getPjName' => ['belongsTo', Term::class, 'pj_name_id'],
        'getPjCharacter' => ['belongsTo', Term::class, 'pj_character_id'],
        "getProdOrders" => ['morphMany', Prod_order::class, 'meta', 'meta_type', 'meta_id'],
    ];

    public function getPjBuilding()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdOrders()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2],$p[3],$p[4]);
    }

    public function getPjLevel()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPjType()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPjName()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPjCharacter()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
