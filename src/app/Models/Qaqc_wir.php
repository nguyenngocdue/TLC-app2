<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_wir extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "project_id", "sub_project_id", "prod_routing_id",
     "prod_discipline_id", "pj_level_id", "pj_module_type_id", "prod_order_id", "priority_id", "due_date", 
     "assignee_to", "wir_description_id", "owner_id"];
    protected $table = "qaqc_wirs";
    protected $primaryKey = 'id';

    public $eloquentParams = [
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getSubProject" => ['belongsTo', Sub_project::class, "sub_project_id"],
        "getProdRouting" => ['belongsTo', Prod_routing::class, "prod_routing_id"],
        "getProdOrder" => ['belongsTo', Prod_order::class, "prod_order_id"],
        "getDiscipline" => ['belongsTo', Prod_discipline::class, "prod_discipline_id"],
        "getWirDescription" => ['belongsTo', Wir_description::class, "wir_description_id"],
        "getPriority" => ['belongsTo', Priority::class, "priority_id"],
        'getAssigneeTo' => ["belongsTo", User::class, 'assignee_to'],
        "getOwnerId" => ["belongsTo", User::class, "owner_id"],
    ];

    public $oracyParams = [
        "getDefMonitors()" => ["getCheckedByField", User::class],
    ];

    public function getProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    
    public function getSubProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRouting()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdOrder()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    
    public function getDiscipline()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWirDescription()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPriority()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssigneeTo()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDefMonitors()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
