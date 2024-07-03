<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Crm_ticketing extends ModelExtended
{
    protected $fillable = [
        'id',
        'house_owner_id', 'house_owner_phone_number', 'house_owner_address', 'house_owner_email',
        'name', 'description', 'unit_id',  'project_id',
        'priority_id', 'due_date',
        'defect_cat_id', 'defect_sub_cat_id',
        'appointment_title',
        'assignee_1', 'assignee_2',
        'appointment_from', 'appointment_to', 'appointment_detail',
        'owner_id', 'status',
    ];

    public static $eloquentParams = [
        "getProject" => ['belongsTo', Project::class, 'project_id'],
        "getUnit" => ['belongsTo', Pj_unit::class, 'unit_id'],
        "getPriority" => ['belongsTo', Priority::class, 'priority_id'],
        "getDefectCat" => ['belongsTo', Crm_ticketing_defect_cat::class, 'defect_cat_id'],
        "getDefectSubCat" => ['belongsTo', Crm_ticketing_defect_sub_cat::class, 'defect_sub_cat_id'],
        "getAssignee1" => ['belongsTo', User::class, 'assignee_1'],
        "getAssignee2" => ['belongsTo', User::class, 'assignee_2'],

        "getMonitors1" => ['belongsToMany', User::class, 'ym2m_crm_ticketing_user_monitor_1'],
        "getHouseOwner" => ['belongsTo', User::class, 'house_owner_id'],

        "getTicketingTasks" => ['hasMany', Crm_ticketing_task::class, 'ticketing_id'],

        "opened_photos" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "closed_photos" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],

        "comment_ticketing" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
    ];

    public function getProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUnit()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPriority()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefectCat()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefectSubCat()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getHouseOwner()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTicketingTasks()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function opened_photos()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function closed_photos()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function comment_ticketing()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}
