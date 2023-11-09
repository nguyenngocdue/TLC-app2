<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Utils\Support\DateTimeConcern;

class Esg_sheet extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "status", "order_no",
        "esg_month", "owner_id", "esg_tmpl_id", "total", "esg_date",
        "esg_master_sheet_id",
    ];

    public static $nameless = true;
    public function getNameAttribute($value)
    {
        $template = $this->getEsgTmpl;
        return ($template->name ?? "Template?") . " - " . DateTimeConcern::convertForLoading("picker_month", $this->esg_month);
    }

    public static $eloquentParams = [
        "getEsgTmpl" => ["belongsTo", Esg_tmpl::class, "esg_tmpl_id"],
        "getMasterSheet" => ["belongsTo", Esg_master_sheet::class, "esg_master_sheet_id"],
        "getLines" => ["hasMany", Esg_sheet_line::class, "esg_sheet_id"],
        // 'getWorkplace' => ['belongsTo', Workplace::class, 'workplace_id'],
        "attachment_esg_sheet" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    // public function getWorkplace()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }

    public function getMasterSheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getEsgTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function attachment_esg_sheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ["dataIndex" => 'id', 'title' => 'Sheet ID', 'no_print' => true, 'invisible' => !true],
            ['dataIndex' => 'esg_master_sheet_id', 'invisible' => true, 'value_as_parent_id' => true],
            ["dataIndex" => 'esg_date', 'read_only_rr2' => true,],
            ["dataIndex" => 'total', 'footer' => 'agg_sum',],
        ];
    }
}
