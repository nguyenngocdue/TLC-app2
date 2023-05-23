<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Sub_project extends ModelExtended
{
    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "slug", "status", "project_id", "owner_id"];

    protected $table = 'sub_projects';

    public $eloquentParams = [
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getProdOrders" => ['hasMany', Prod_order::class],
    ];

    public $oracyParams = [
        "getProjectMembers()" => ['getCheckedByField', User::class],
    ];

    public function getProjectMembers()
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

    function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id'],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
        ];
    }
}
