<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "control_type_id", "value", "value_on_hold",
        "qaqc_insp_chklst_sht_id", "qaqc_insp_group_id",
        "qaqc_insp_control_value_id", "qaqc_insp_control_group_id", "owner_id", "inspector_id"
    ];
    protected $table = "qaqc_insp_chklst_lines";

    public $eloquentParams = [
        "getGroup" => ["belongsTo", Qaqc_insp_group::class, "qaqc_insp_group_id"],
        "getSheet" => ["belongsTo", Qaqc_insp_chklst_sht::class, "qaqc_insp_chklst_sht_id"],
        "getControlGroup" => ["belongsTo", Qaqc_insp_control_group::class, "qaqc_insp_control_group_id"],
        "getControlValue" => ["belongsTo", Qaqc_insp_control_value::class, "qaqc_insp_control_value_id"],
        "getControlType" => ["belongsTo", Control_type::class, "control_type_id"],
        "insp_photos" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        // "getProject" => ["morphMany", Project::class, "complex"],
        // "getSubProject" => ["morphMany", Sub_Project::class, "complex"],
        // "getProdRouting" => ["morphMany", Prod_routing::class, "complex"],
        // "getProdOrder" => ["morphMany", Prod_order::class, "complex"],
        "getNcrs" => ['morphMany', Qaqc_ncr::class, 'parent', 'parent_type', 'parent_id'],
        "getOwnerId" => ["belongsTo", User::class, "owner_id"],
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

    public function getGroup()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getSheet()
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

    public function getNcrs()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }

    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProject()
    {
        $tmp = $this->getRun->getSheet->getChklst->prodOrder->subProject;
        $relation = $tmp->belongsTo(Project::class, 'project_id');
        return $relation;
    }

    public function getSubProject()
    {
        $tmp = $this->getRun->getSheet->getChklst->prodOrder;
        $relation = $tmp->belongsTo(Sub_project::class, 'sub_project_id');
        return $relation;
    }

    public function getProdRouting()
    {
        $tmp = $this->getRun->getSheet->getChklst->prodOrder;
        $relation = $tmp->belongsTo(Prod_routing::class, 'prod_routing_id');
        return $relation;
    }

    public function getProdOrder()
    {
        $tmp = $this->getRun->getSheet->getChklst;
        $relation = $tmp->belongsTo(Prod_order::class, 'prod_order_id');
        return $relation;
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'qaqc_insp_group_id', 'title' => "Group"],
            ['dataIndex' => 'qaqc_insp_chklst_sht_id', 'title' => 'Sheet ID', 'invisible' => !true, 'value_as_parent_id' => true],
            // ['dataIndex' => 'qaqc_insp_chklst_run_id', 'title' => "Run"],
            ['dataIndex' => 'qaqc_insp_control_group_id', 'title' => "Control Group"],
            ['dataIndex' => 'qaqc_insp_control_value_id', 'title' => "Control Value"],
            ['dataIndex' => 'control_type_id', 'title' => "Control Type"],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'value'],
            ['dataIndex' => 'value_on_hold'],
            // ['dataIndex' => 'value_comment'],
        ];
    }
}
