<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_filter_mode extends ModelExtended
{
    protected $fillable = ["id", "name", "title", "report_id", "report_access_id", "stored_filter_key", "owner_id"];

    public static $eloquentParams = [
        "getParent" => ['belongsTo', Rp_report::class, 'report_id'],
        "getReportAccess" => ['belongsTo', Rp_report::class, 'report_access_id'],
    ];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getReportAccess()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }


    public function getManyLineParams()
    {
        return [
            ["dataIndex" => "id", /* "invisible" => true, */],
            ["dataIndex" => "report_id", "value_as_parent_id" => true, "invisible" => true,],
            ["dataIndex" => "name"],
            ["dataIndex" => "report_access_id"],
            ["dataIndex" => "stored_filter_key"]
        ];
    }
}
