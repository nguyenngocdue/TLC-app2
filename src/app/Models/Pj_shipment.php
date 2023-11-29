<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_shipment extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "project_id", "sub_project_id", "external_doc_link", "owner_id"];

    public static $statusless = true;

    public static $eloquentParams = [
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getSubProject" => ['belongsTo', Sub_project::class, "sub_project_id"],

        "getPjModules" => ['hasMany', Pj_module::class, "pj_shipment_id"],
    ];

    public function getPjModules()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getSubProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
