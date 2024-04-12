<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_punchlist_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description",
        "prod_discipline_id", "status", "remark", "owner_id",
        "material_supply", "scope", "is_original_scope",
        "order_no", "qaqc_punchlist_id",
    ];

    public static $nameless = true;

    public static $eloquentParams = [
        "getProdDiscipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],
        "getPunchlist" => ["belongsTo", Qaqc_punchlist::class, 'qaqc_punchlist_id'],
        "punchlist_photos" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];
    public function punchlist_photos()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getProdDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPunchlist()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => !true],
            ['dataIndex' => 'qaqc_punchlist_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'description', 'title' => "Inspection Comment"],
            ['dataIndex' => 'prod_discipline_id', "cloneable" => true,],
            ['dataIndex' => 'status', "cloneable" => true,],
            ['dataIndex' => 'remark', "cloneable" => true,],
            ['dataIndex' => 'material_supply', "cloneable" => true,],
            ['dataIndex' => 'scope', "cloneable" => true,],
            ['dataIndex' => 'is_original_scope', "cloneable" => true,],
            ['dataIndex' => 'punchlist_photos', 'title' => 'Photos',],
        ];
    }
}
