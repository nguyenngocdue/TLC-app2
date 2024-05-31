<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Eco_sheet extends ModelExtended
{
    public static $hasDueDate = true;

    protected $fillable = [
        "id", "name", "description", "slug", "revision_no", "priority_id", "project_id",
        "assignee_1", "assignee_2", "assignee_3", "total_labor_cost", "currency_1", "currency_2",
        "total_add_cost", "total_remove_cost", "total_material_cost", "due_date", "closed_at", "status",
        "order_no", "owner_id"
    ];

    public static $eloquentParams = [
        'getProject' => ['belongsTo', Project::class, 'project_id'],
        'getPriority' => ['belongsTo', Priority::class, 'priority_id'],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        'getAssignee2' => ['belongsTo', User::class, 'assignee_2'],
        'getAssignee3' => ['belongsTo', User::class, 'assignee_3'],
        'getCurrency1' => ['belongsTo', Act_currency::class, 'currency_1'],
        'getCurrency2' => ['belongsTo', Act_currency::class, 'currency_2'],
        'getEffectivenessLines' => ['hasMany', Eco_effectiveness_line::class, 'eco_sheet_id'],
        'getTakenActions' => ['hasMany', Eco_taken_action::class, 'eco_sheet_id'],
        'getLaborImpacts' => ['hasMany', Eco_labor_impact::class, 'eco_sheet_id'],
        'getMaterialImpactAdds' => ['hasMany', Eco_material_impact_add::class, 'eco_sheet_id'],
        'getMaterialImpactRemoves' => ['hasMany', Eco_material_impact_remove::class, 'eco_sheet_id'],
        "attachment_eco_sht" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],

        "signature_eco_peers" => ['morphMany', Signature::class, 'signable', 'signable_type', 'signable_id'],
        "signature_eco_managers" => ['morphMany', Signature::class, 'signable', 'signable_type', 'signable_id'],

        "getMonitors1" => ["belongsToMany", User::class, 'ym2m_eco_sheet_user_monitor_1'],
        "getMonitors2" => ["belongsToMany", User::class, 'ym2m_eco_sheet_user_monitor_2'],
        "getMonitors3" => ["belongsToMany", User::class, 'ym2m_eco_sheet_user_monitor_3'],
        "getSubProjectsOfEco" => ["belongsToMany", Sub_project::class, 'ym2m_eco_sheet_sub_project'],
        "getTypesOfChangeOfEco" => ["belongsToMany", Term::class, 'ym2m_eco_sheet_term_type_of_change'],
        "signature_eco_peers_list" => ["belongsToMany", User::class, 'ym2m_eco_sheet_user_peer'],
        "signature_eco_managers_list" => ["belongsToMany", User::class, 'ym2m_eco_sheet_user_manager'],
    ];

    public function signature_eco_peers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function signature_eco_managers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function getMaterialImpactAdds()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMaterialImpactRemoves()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function attachment_eco_sht()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getEffectivenessLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTakenActions()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getLaborImpacts()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee3()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPriority()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCurrency1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCurrency2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMonitors1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors3()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function signature_eco_peers_list()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function signature_eco_managers_list()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getSubProjectsOfEco()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTypesOfChangeOfEco()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
