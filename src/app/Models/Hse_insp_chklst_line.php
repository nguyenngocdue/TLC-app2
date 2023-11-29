<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_insp_chklst_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "control_type_id", "value", "order_no",
        "hse_insp_chklst_sht_id", "hse_insp_group_id",
        "hse_insp_control_value_id", "hse_insp_control_group_id", "owner_id"
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "getGroup" => ["belongsTo", Hse_insp_group::class, "hse_insp_group_id"],
        "getSheet" => ["belongsTo", Hse_insp_chklst_sht::class, "hse_insp_chklst_sht_id"],
        "getControlGroup" => ["belongsTo", Hse_insp_control_group::class, "hse_insp_control_group_id"],
        "getControlValue" => ["belongsTo", Hse_insp_control_value::class, "hse_insp_control_value_id"],
        "getControlType" => ["belongsTo", Control_type::class, "control_type_id"],
        "insp_photos" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "insp_comments" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "getCorrectiveActions" => ['morphMany', Hse_corrective_action::class, 'correctable', 'correctable_type', 'correctable_id'],
    ];

    public static $oracyParams = [];

    public function insp_photos()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function insp_comments()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category')->orderBy('updated_at');
    }

    public function getGroup()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getSheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlGroup()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlValue()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getInspector()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCorrectiveActions()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'hse_insp_chklst_sht_id', 'title' => 'Sheet ID', 'invisible' => !true, 'value_as_parent_id' => true],
            ['dataIndex' => 'hse_insp_group_id',],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'control_type_id',],
            ['dataIndex' => 'hse_insp_control_group_id',],
            ['dataIndex' => 'hse_insp_control_value_id',],
            ['dataIndex' => 'value'],
        ];
    }
}
