<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_shipment extends ModelExtended
{
    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "slug", "project_id", "sub_project_id", "external_doc_link", "owner_id"];

    protected $table = 'pj_shipments';

    public $eloquentParams = [
        "getPjModules" => ['hasMany', Pj_module::class, "pj_shipment_id"],
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getSubProject" => ['belongsTo', Sub_project::class, "sub_project_id"],
        "getOwner" => ['belongsTo', User::class, 'owner_id'],
    ];

    public function getPjModules()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
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
    public function getOwner()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
