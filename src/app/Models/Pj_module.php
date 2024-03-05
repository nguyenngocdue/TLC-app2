<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_module extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug",  "pj_building_id", "pj_level_id", "pj_module_type_id",
        "pj_name_id", "pj_character_id", "pj_unit_id", "pj_shipment_id", "owner_id",
        "sub_project_id", "length",  "width", "height", "weight", "manufactured_year", "plot_number",
        "insp_chklst_link", "shipping_doc_link",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        'getPjBuilding' => ['belongsTo', Term::class, 'pj_building_id'],
        'getPjLevel' => ['belongsTo', Term::class, 'pj_level_id'],
        'getPjType' => ['belongsTo', Term::class, 'pj_module_type_id'],
        'getPjName' => ['belongsTo', Term::class, 'pj_name_id'],
        'getPjCharacter' => ['belongsTo', Term::class, 'pj_character_id'],
        'getPjUnit' => ['belongsTo', Pj_unit::class, 'pj_unit_id'],
        'getPjShipment' => ['belongsTo', Pj_shipment::class, 'pj_shipment_id'],

        'getPjPods' => ['hasMany', Pj_pod::class, 'pj_module_id'],

        "getProdOrders" => ['morphMany', Prod_order::class, 'meta', 'meta_type', 'meta_id'],
        "getSubProject" => ['belongsTo', Sub_project::class, "sub_project_id"],
    ];

    public function getPjBuilding()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPjPods()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdOrders()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }

    public function getPjLevel()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPjType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPjName()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPjCharacter()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPjUnit()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPjShipment()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
