<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Listener_config extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'owner_id',
        'project_id', 'sub_project_id', 'prod_routing_id', 'qaqc_insp_tmpl_id',
    ];
    protected static $statusless = true;

    public static $eloquentParams = [
        'getProject' => ['belongsTo', Project::class, 'project_id'],
        "getSubProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
        "getRouting" => ['belongsTo', Prod_routing::class, 'prod_routing_id'],
        "getQaqcInspTmpl" => ["belongsTo", Qaqc_insp_tmpl::class, "qaqc_insp_tmpl_id"],
    ];

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
    public function getRouting()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getQaqcInspTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
