<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Eco_sheet extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug", "revision_no", "priority_id", "project_id",
        "assignee_1", "assignee_2", "assignee_3", "total_labor_cost", "currency_1", "currency_2",
        "total_add_cost", "total_remove_cost", "total_material_cost", "due_date", "closed_at", "status",
        "order_no", "owner_id"
    ];
    protected $table = "eco_sheets";

    public $eloquentParams = [
        'getProject' => ['belongsTo', Project::class, 'project_id'],
        'getPriority' => ['belongsTo', Priority::class, 'priority_id'],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        'getAssignee2' => ['belongsTo', User::class, 'assignee_2'],
        'getAssignee3' => ['belongsTo', User::class, 'assignee_3'],
        'getCurrency1' => ['belongsTo', Currency::class, 'currency_1'],
        'getCurrency2' => ['belongsTo', Currency::class, 'currency_2'],
        'getEffectivenessLines' => ['hasMany', Eco_effectiveness_line::class, 'eco_sheet_id'],
        'getTakenActions' => ['hasMany', Eco_taken_action::class, 'eco_sheet_id'],
        'getLaborImpacts' => ['hasMany', Eco_labor_impact::class, 'eco_sheet_id'],
        'getMaterialImpactAdds' => ['hasMany', Eco_material_impact_add::class, 'eco_sheet_id'],
        'getMaterialImpactRemoves' => ['hasMany', Eco_material_impact_remove::class, 'eco_sheet_id'],
        "attachment_eco_sht" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "getSignatures1" => ["hasMany", Eco_signature::class, "eco_sheet_id"],
        "getSignatures2" => ["hasMany", Eco_signature::class, "eco_sheet_id"],
    ];

    public $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
        "getMonitors2()" => ["getCheckedByField", User::class],
        "getSubProject()" => ["getCheckedByField", Sub_project::class],
    ];
    public function getSignatures1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getSignatures2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMaterialImpactAdds()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMaterialImpactRemoves()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function attachment_eco_sht()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getEffectivenessLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTakenActions()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getLaborImpacts()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee3()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPriority()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCurrency1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCurrency2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMonitors1()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getMonitors2()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getSubProject()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
