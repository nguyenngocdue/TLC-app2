<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_ncr extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug", "project_id", "sub_project_id",
        "parent_id", "parent_type", "prod_routing_id", "prod_order_id", "prod_discipline_1_id",
        "prod_discipline_id", "prod_discipline_2_id", "user_team_id", "priority_id", "due_date",
        "assignee_to", "cause_analysis"
    ];
    protected $primaryKey = 'id';
    protected $table = "qaqc_ncrs";

    public $eloquentParams = [
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getSubProject" => ['belongsTo', Sub_project::class, "sub_project_id"],
        "getProdRouting" => ['belongsTo', Prod_routing::class, "prod_routing_id"],
        "getProdOrder" => ['belongsTo', Prod_order::class, "prod_order_id"],
        "getDiscipline" => ['belongsTo', Prod_discipline::class, "prod_discipline_id"],
        "getDiscipline1" => ['belongsTo', Prod_discipline_1::class, "prod_discipline_1_id"],
        "getDiscipline2" => ['belongsTo', Prod_discipline_2::class, "prod_discipline_2_id"],
        "getUserTeam" => ['belongsTo', User_team::class, "user_team_id"],
        "getPriority" => ['belongsTo', Priority::class, "priority_id"],
        'getAssigneeTo' => ["belongsTo", User::class, 'assignee_to'],
        "getInterSubcon" => ["belongsTo", Term::class, 'inter_subcon_id'],
        "getDefectRootCause" => ["belongsTo", Term::class, 'defect_root_cause_id'],
        "getDefectDisposition" => ["belongsTo", Term::class, 'defect_disposition_id'],
        "getParent" => ['morphTo', Qaqc_ncr::class, 'parent_type', 'parent_id'],
        'getQaqcCars' => ['hasMany', Qaqc_car::class, 'qaqc_ncr_id'],
    ];

    public $oracyParams = [
        "getDefMonitors()" => ["getCheckedByField", User::class],
    ];

    public function getParent()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

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
    public function getDiscipline1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDiscipline2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserTeam()
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
    public function getInterSubcon()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefectRootCause()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefectDisposition()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getQaqcCars()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefMonitors()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
