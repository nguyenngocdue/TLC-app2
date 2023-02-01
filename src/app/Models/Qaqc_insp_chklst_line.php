<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "control_type_id", "value", "value_on_hold", "value_comment",
        "qaqc_insp_chklst_run_id", "qaqc_insp_group_id",
        "qaqc_insp_control_value_id", "qaqc_insp_control_group_id",
    ];
    protected $table = "qaqc_insp_chklst_lines";

    public $eloquentParams = [
        // "getChklst" => ["belongsTo", Qaqc_insp_chklst::class, "qaqc_insp_chklst_id"],
        // "getSheet" => ["belongsTo", Qaqc_insp_sheet::class, "qaqc_insp_sheet_id"],
        "getGroup" => ["belongsTo", Qaqc_insp_group::class, "qaqc_insp_group_id"],
        "getRun" => ["belongsTo", Qaqc_insp_chklst_run::class, "qaqc_insp_chklst_run_id"],
        "getControlGroup" => ["belongsTo", Qaqc_insp_control_group::class, "qaqc_insp_control_group_id"],
        "getControlValue" => ["belongsTo", Qaqc_insp_control_value::class, "qaqc_insp_control_value_id"],
        "getControlType" => ["belongsTo", Control_type::class, "control_type_id"],
        "insp_photos" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public $oracyParams = [
        // "getNoOfYesNo()" => ["getCheckedByField", Qaqc_insp_value::class],
        // "getOnHoldOfYesNo()" => ["getCheckedByField", Qaqc_insp_value::class],
        // "getFailedOfPassFail()" => ["getCheckedByField", Qaqc_insp_value::class],
        // "getOnHoldOfPassFail()" => ["getCheckedByField", Qaqc_insp_value::class],
    ];

    public function insp_photos()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    // public function getNoOfYesNo()
    // {
    //     $p = $this->oracyParams[__FUNCTION__ . '()'];
    //     return $this->{$p[0]}(__FUNCTION__, $p[1]);
    // }
    // public function getOnHoldOfYesNo()
    // {
    //     $p = $this->oracyParams[__FUNCTION__ . '()'];
    //     return $this->{$p[0]}(__FUNCTION__, $p[1]);
    // }
    // public function getFailedOfPassFail()
    // {
    //     $p = $this->oracyParams[__FUNCTION__ . '()'];
    //     return $this->{$p[0]}(__FUNCTION__, $p[1]);
    // }
    // public function getOnHoldOfPassFail()
    // {
    //     $p = $this->oracyParams[__FUNCTION__ . '()'];
    //     return $this->{$p[0]}(__FUNCTION__, $p[1]);
    // }
    public function getGroup()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getRun()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlGroup()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlType()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlValue()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', "renderer" => "id", "align" => "center", "type" => "qaqc_insp_chklst_lines"],
            ['dataIndex' => 'getChklst', 'title' => "Checklist", 'renderer' => "column", "rendererParam" => "description"],
            // ['dataIndex' => 'getSheet', 'title' => "Sheet", 'renderer' => "column", "rendererParam" => "description"],
            // ['dataIndex' => 'getGroup', "title" => "Group", 'renderer' => "column", "rendererParam" => "description"],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'control_type'],
        ];
    }
}
