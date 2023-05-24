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

        'parent_id',
        'order_no',
        'owner_id',
    ];
    protected $table = "zunit_test_02s";
    protected static $statusless = true;

    public $eloquentParams = [
        "radioYesNo" => ['belongsTo', Qaqc_insp_control_value::class, 'radio_yes_no'],
        "radioPassFail" => ['belongsTo', Qaqc_insp_control_value::class, 'radio_pass_fail'],
        "dropdownYesNo" => ['belongsTo', Qaqc_insp_control_value::class, 'dropdown_yes_no'],
        "dropdownPassFail" => ['belongsTo', Qaqc_insp_control_value::class, 'dropdown_pass_fail'],
    ];

    public $oracyParams = [
        "checkboxYesNo()" => ["getCheckedByField", Qaqc_insp_control_value::class],
        "checkboxPassFail()" => ["getCheckedByField", Qaqc_insp_control_value::class],
        "dropdownMultiYesNo()" => ["getCheckedByField", Qaqc_insp_control_value::class],
        "dropdownMultiPassFail()" => ["getCheckedByField", Qaqc_insp_control_value::class],
    ];

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
            ['dataIndex' => 'checkboxYesNo()', 'cloneable' => true],
            ['dataIndex' => 'checkboxPassFail()', 'cloneable' => true],
            ['dataIndex' => 'dropdownMultiYesNo()', 'cloneable' => true],
            ['dataIndex' => 'dropdownMultiPassFail()', 'cloneable' => true],

        ];
    }

    public function radioYesNo()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function radioPassFail()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function dropdownYesNo()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function dropdownPassFail()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }


    public function checkboxYesNo()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function checkboxPassFail()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function dropdownMultiYesNo()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function dropdownMultiPassFail()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
