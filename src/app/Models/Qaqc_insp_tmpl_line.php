<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_tmpl_line extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "description",
        "control_type_id",
        "qaqc_insp_group_id",
        "qaqc_insp_control_group_id",
        "owner_id",
        "qaqc_insp_tmpl_sht_id",
        "col_span",
        "checkpoint_level",

        "order_no",
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "getGroup" => ["belongsTo", Qaqc_insp_group::class, "qaqc_insp_group_id"],
        "getSheet" => ["belongsTo", Qaqc_insp_tmpl_sht::class, "qaqc_insp_tmpl_sht_id"],
        "getControlType" => ["belongsTo", Control_type::class, "control_type_id"],
        "getControlGroup" => ["belongsTo", Qaqc_insp_control_group::class, "qaqc_insp_control_group_id"],
    ];

    public function getSheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getGroup()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getControlType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getControlGroup()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true, 'no_print' => true],
            ['dataIndex' => 'id', 'invisible' => true,],
            ['dataIndex' => 'qaqc_insp_tmpl_sht_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'qaqc_insp_group_id',],
            ['dataIndex' => 'name'],
            // ['dataIndex' => 'description'],
            ['dataIndex' => 'control_type_id', 'cloneable' => true],
            ['dataIndex' => 'qaqc_insp_control_group_id', 'cloneable' => true],
            ['dataIndex' => 'col_span', 'cloneable' => true],
            ['dataIndex' => 'checkpoint_level', 'cloneable' => true],
        ];
    }
}
