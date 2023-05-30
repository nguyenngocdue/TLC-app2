<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Sub_project extends ModelExtended
{
    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "slug", "status", "project_id", "owner_id", 'lod_id'];

    protected $table = 'sub_projects';

    public $eloquentParams = [
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getProdOrders" => ['hasMany', Prod_order::class],
        "getLod" => ['belongsTo', Term::class, "lod_id"],
    ];

    public $oracyParams = [
        "getProjectMembers()" => ['getCheckedByField', User::class],
        "getProdRoutings()" => ['getCheckedByField', Prod_routing::class],
    ];

    public function getProjectMembers()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getProdRoutings()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getProdOrders()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function getProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getLod()
    {
        $p = $this->eloquentParams[__FUNCTION__];
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
