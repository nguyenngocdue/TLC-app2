<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_02 extends ModelExtended
{
    protected $fillable = [
        'id',
        'name',
        'radio_yes_no',
        'radio_pass_fail',
        'dropdown_yes_no',
        'dropdown_pass_fail',
        'searchable_dialog_yes_no',
        'searchable_dialog_pass_fail',

        'parent_id',
        'order_no',
        'owner_id',
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "radioYesNo" => ['belongsTo', Qaqc_insp_control_value::class, 'radio_yes_no'],
        "radioPassFail" => ['belongsTo', Qaqc_insp_control_value::class, 'radio_pass_fail'],
        "dropdownYesNo" => ['belongsTo', Qaqc_insp_control_value::class, 'dropdown_yes_no'],
        "dropdownPassFail" => ['belongsTo', Qaqc_insp_control_value::class, 'dropdown_pass_fail'],
        "searchableDialogYesNo" => ['belongsTo', Qaqc_insp_control_value::class, 'searchable_dialog_yes_no'],
        "searchableDialogPassFail" => ['belongsTo', Qaqc_insp_control_value::class, 'searchable_dialog_pass_fail'],
        //Many to Many
        "checkboxYesNo" => ["belongsToMany", Qaqc_insp_control_value::class, "ym2m_qaqc_insp_control_value_zunit_test_02_checkbox_yes_no",],
        "checkboxPassFail" => ["belongsToMany", Qaqc_insp_control_value::class, "ym2m_qaqc_insp_control_value_zunit_test_02_checkbox_pass_fail",],
        "dropdownMultiYesNo" => ["belongsToMany", Qaqc_insp_control_value::class, "ym2m_qaqc_insp_control_value_zunit_test_02_dm_yes_no",],
        "dropdownMultiPassFail" => ["belongsToMany", Qaqc_insp_control_value::class, "ym2m_qaqc_insp_control_value_zunit_test_02_dm_pass_fail",],
        "searchableDialogMultiYesNo" => ["belongsToMany", Qaqc_insp_control_value::class, "ym2m_qaqc_insp_control_value_zunit_test_02_smd_yes_no",],
        "searchableDialogMultiPassFail" => ["belongsToMany", Qaqc_insp_control_value::class, "ym2m_qaqc_insp_control_value_zunit_test_02_smd_pass_fail",],
    ];

    public function radioYesNo()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function radioPassFail()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function dropdownYesNo()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function dropdownPassFail()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function searchableDialogYesNo()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function searchableDialogPassFail()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function checkboxYesNo()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function checkboxPassFail()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function dropdownMultiYesNo()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function dropdownMultiPassFail()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function searchableDialogMultiYesNo()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function searchableDialogMultiPassFail()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'parent_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name', 'cloneable' => true],
            ['dataIndex' => 'radio_yes_no', 'cloneable' => true],
            ['dataIndex' => 'radio_pass_fail', 'cloneable' => true],
            ['dataIndex' => 'dropdown_yes_no', 'cloneable' => true],
            ['dataIndex' => 'dropdown_pass_fail', 'cloneable' => true],
            ['dataIndex' => 'searchable_dialog_yes_no', 'cloneable' => true],
            ['dataIndex' => 'searchable_dialog_pass_fail', 'cloneable' => true],

            ['dataIndex' => 'checkboxYesNo', 'cloneable' => true],
            ['dataIndex' => 'checkboxPassFail', 'cloneable' => true],
            ['dataIndex' => 'dropdownMultiYesNo', 'cloneable' => true],
            ['dataIndex' => 'dropdownMultiPassFail', 'cloneable' => true],
            ['dataIndex' => 'searchableDialogMultiYesNo', 'cloneable' => true],
            ['dataIndex' => 'searchableDialogMultiPassFail', 'cloneable' => true],

        ];
    }
}
