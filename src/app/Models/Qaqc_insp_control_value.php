<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_control_value extends ModelExtended
{
    protected $fillable = [
        "id",
        "control_group",
        "name",
        "description",
        'qaqc_insp_control_group_id',
        "color",
        "behavior_of",

        'owner_id',
        "order_no",
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "getBehaviorOf" => ["belongsTo", Term::class, "behavior_of"],
        "getControlGroup" => ["belongsTo", Qaqc_insp_control_group::class, "qaqc_insp_control_group_id"],
        "getValues" => ["hasMany", Qaqc_insp_value::class, "qaqc_insp_control_value_id"],
    ];

    public function getBehaviorOf()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getValues()
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
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'qaqc_insp_control_group_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'color'],
            ['dataIndex' => 'behavior_of'],
        ];
    }
}
