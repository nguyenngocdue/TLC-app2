<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Http\Traits\HasCheckbox;

class Qaqc_insp_chklst_line extends ModelExtended
{
    use HasCheckbox;
    protected $fillable = [
        "id", "name", "description", "control_type", "value",
        "qaqc_insp_chklst_id", "qaqc_insp_sheet_id", "qaqc_insp_group_id",
        "qaqc_insp_control_value_id", "qaqc_insp_control_group_id",
    ];
    protected $table = "qaqc_insp_chklst_lines";

    public $eloquentParams = [
        "getChklst" => ["belongsTo", Qaqc_insp_chklst::class, "qaqc_insp_chklst_id"],
        "getSheet" => ["belongsTo", Qaqc_insp_sheet::class, "qaqc_insp_sheet_id"],
        "getGroup" => ["belongsTo", Qaqc_insp_group::class, "qaqc_insp_group_id"],
        "getControlGroup" => ["belongsTo", Qaqc_insp_control_group::class, "qaqc_insp_control_group_id"],
        "getControlValue" => ["belongsTo", Qaqc_insp_control_value::class, "qaqc_insp_control_value_id"],
        "getFailDetail" => ["belongsToMany", Qaqc_insp_value::class, "qaqc_insp_fail_details", 'qaqc_insp_chklst_line_id', 'qaqc_insp_value_id'],
        "getOnHoldDetail" => ["belongsToMany", Qaqc_insp_value::class, "qaqc_insp_onhold_details", 'qaqc_insp_chklst_line_id', 'qaqc_insp_value_id'],
    ];

    public $oracyParams = [
        "getOnHold()" => ["getCheckedByField", Qaqc_insp_value::class],
        "getFailed()" => ["getCheckedByField", Qaqc_insp_value::class],
    ];

    public function getOnHold()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getFailed()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getChklst()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getSheet()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getGroup()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlGroup()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlValue()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getFailDetail()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
    public function getOnHoldDetail()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', "renderer" => "id", "align" => "center", "type" => "qaqc_insp_chklst_lines"],
            ['dataIndex' => 'getChklst', 'title' => "Checklist", 'renderer' => "column", "rendererParam" => "description"],
            ['dataIndex' => 'getSheet', 'title' => "Sheet", 'renderer' => "column", "rendererParam" => "description"],
            ['dataIndex' => 'getGroup', "title" => "Group", 'renderer' => "column", "rendererParam" => "description"],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'control_type'],
        ];
    }
}
