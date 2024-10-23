<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_filter_link extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "title",
        "stored_filter_key",
        "report_id",
        "report_filter_link_id",
        "order_no",
        "owner_id"
    ];

    public static $statusless = true;
    public static $nameLess = true;

    public static $eloquentParams = [
        "getReport" => ['belongsTo', Rp_report::class, 'report_id'],
        "getRpFilterLink" => ['belongsTo', Rp_report::class, 'report_filter_link_id'],
    ];

    public function getRpFilterLink()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getReport()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }


    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'id',],
            ["dataIndex" => 'order_no',],
            ["dataIndex" => 'title',],
            ["dataIndex" => 'stored_filter_key'],
            ["dataIndex" => 'report_id', 'value_as_parent_id' => true, 'invisible' => true, ],
            ["dataIndex" => 'report_filter_link_id'],
        ];
    }
}
