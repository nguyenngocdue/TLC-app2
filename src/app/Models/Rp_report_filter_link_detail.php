<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_report_filter_link_detail extends ModelExtended
{
    protected $fillable = [
        "id",
        "rp_report_id",
        "rp_filter_link_id",
        "order_no",
        "owner_id"
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getReport" => ['belongsTo', Rp_report::class, 'rp_report_id'],
        "getFilterLink" => ['belongsTo', Rp_filter_link::class, 'rp_filter_link_id'],
    ];

    public function getFilterLink()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getReport()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }


    // public function getManyLineParams()
    // {
    //     return [
    //         ["dataIndex" => 'id', 'invisible' => !true,],
    //         ["dataIndex" => 'order_no', /* 'invisible' => true, */],
    //         ["dataIndex" => 'rp_page_id', 'value_as_parent_id' => true, 'invisible' => true,],
    //         ["dataIndex" => 'rp_block_id', /*'renderer' => 'column_link', /*'rendererParam' => 'name'*/],
    //         ["dataIndex" => 'col_span'],
    //         ["dataIndex" => 'attachment_background'],
    //     ];
    // }
}
