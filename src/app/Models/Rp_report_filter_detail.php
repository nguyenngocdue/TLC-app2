<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_report_filter_detail extends ModelExtended
{
    protected $fillable = [
        "id", /* "title", */
        "rp_column_id", "rp_report_id",
        "bw_list_ids", "black_or_white",
        "is_required", "default_value",
        "listen_reducer_id", "allow_clear",
        "is_multiple", "control_type",
        "order_no", "owner_id",
    ];

    public static $eloquentParams = [
        'getBlackOrWhite' => ['belongsTo', Term::class, 'black_or_white'],
        'getControlType' => ['belongsTo', Term::class, 'control_type'],
        'getReport' => ['belongsTo', Rp_report::class, 'rp_report_id'],
        'getColumn' => ['belongsTo', Rp_column::class, 'rp_column_id'],
        'getListenReducer' => ['belongsTo', Rp_listen_reducer::class, 'listen_reducer_id'],

    ];

    public function getBlackOrWhite()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getReport()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getColumn()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getListenReducer()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyLineParamsFilterDetails()
    {
        return [
            ["dataIndex" => 'id'],
            ["dataIndex" => 'order_no', /* 'invisible' => true, */],
            ["dataIndex" => 'rp_report_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ["dataIndex" => 'rp_column_id'],
            ["dataIndex" => 'bw_list_ids'],
            ["dataIndex" => 'black_or_white'],
            ["dataIndex" => 'is_required'],
            ["dataIndex" => 'default_value'],
            ["dataIndex" => 'allow_clear'],
            ["dataIndex" => 'is_multiple'],
            ["dataIndex" => 'control_type'],
        ];
    }
}
