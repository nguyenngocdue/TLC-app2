<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Sub_project extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug", "status", "project_id",
        "owner_id", 'lod_id', "client_id"
    ];

    public static $eloquentParams = [
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getProdOrders" => ['hasMany', Prod_order::class],
        "getLod" => ['belongsTo', Term::class, "lod_id"],
        "getClient" => ["belongsTo", User_company::class, "client_id"],

        "attachment_subproject_homeowner_manual" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_subproject_project_plans" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public static $oracyParams = [
        "getProjectMembers()" => ['getCheckedByField', User::class],
        "getProdRoutingsOfSubProject()" => ['getCheckedByField', Prod_routing::class],

        "getProjectClientsOfSubProject()" => ['getCheckedByField', User::class],
        "getExternalInspectorsOfSubProject()" => ['getCheckedByField', User::class],
        // "getApartmentOwnersOfSubProject()" => ['getCheckedByField', User::class],
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
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getProdRoutingsOfSubProject()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getClientsOfSubProject()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getExternalInspectorsOfSubProject()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    // public function getApartmentOwnersOfSubProject()
    // {
    //     $p = static::$oracyParams[__FUNCTION__ . '()'];
    //     return $this->{$p[0]}(__FUNCTION__, $p[1]);
    // }

    public function getProdOrders()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function getProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getLod()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getClient()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id'],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'status'],
        ];
    }
}
