<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Sub_project extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "status", "project_id", "owner_id", 'lod_id'];
    protected $table = 'sub_projects';

    public static $eloquentParams = [
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getProdOrders" => ['hasMany', Prod_order::class],
        "getLod" => ['belongsTo', Term::class, "lod_id"],
    ];

    public static $oracyParams = [
        "getProjectMembers()" => ['getCheckedByField', User::class],
        "getProdRoutingsOfSubProject()" => ['getCheckedByField', Prod_routing::class],

        "getProjectClientsOfSubProject()" => ['getCheckedByField', User::class],
        "getExternalInspectorsOfSubProject()" => ['getCheckedByField', User::class],
        "getApartmentOwnersOfSubProject()" => ['getCheckedByField', User::class],
    ];

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
    public function getApartmentOwnersOfSubProject()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

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
