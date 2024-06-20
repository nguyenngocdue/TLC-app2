<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Sub_project extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug", "status", "project_id",
        "qr_plate_style_id", "owner_id", 'lod_id', "client_id"
    ];

    public static $eloquentParams = [
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getProdOrders" => ['hasMany', Prod_order::class],
        "getCurrentLod" => ['belongsTo', Pj_task_phase::class, "lod_id"],
        "getClient" => ["belongsTo", User_company::class, "client_id"],
        "getQrPlateStyle" => ["belongsTo", Term::class, "qr_plate_style_id"],

        "attachment_subproject_homeowner_manual" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_subproject_project_plans" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        //Many to many
        "getProjectMembers" => ['belongsToMany', User::class, "ym2m_sub_project_user_project_member"],
        "getProdRoutingsOfSubProject" => ['belongsToMany', Prod_routing::class, "ym2m_prod_routing_sub_project"],

        "getProjectClientsOfSubProject" => ['belongsToMany', User::class, "ym2m_sub_project_user_project_client"],
        "getExternalInspectorsOfSubProject" => ['belongsToMany', User::class, "ym2m_sub_project_user_ext_insp"],
        "getCouncilMembersOfSubProject" => ['belongsToMany', User::class, "ym2m_sub_project_user_council_member"],
    ];

    public function attachment_subproject_homeowner_manual()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function attachment_subproject_project_plans()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getProjectMembers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRoutingsOfSubProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getClientsOfSubProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getExternalInspectorsOfSubProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCouncilMembersOfSubProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getProjectClientsOfSubProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdOrders()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function getQrPlateStyle()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function getProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getCurrentLod()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getClient()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id'],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'status'],
        ];
    }
}
