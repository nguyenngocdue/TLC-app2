<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Sub_project extends ModelExtended
{
    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "slug", "status", "project_id", "owner_id"];
    protected $primaryKey = 'id';
    protected $table = 'sub_projects';

    public $eloquentParams = [
        "prodOrders" => ['hasMany', Prod_order::class],
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getOwner" => ["belongsTo", User::class, "owner_id"],
    ];

    public function prodOrders()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function getProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getOwner()
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
