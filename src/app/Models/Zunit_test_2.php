<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_2 extends ModelExtended
{
    protected $fillable = ['content', 'radio_yes_no', 'radio_pass_fail', 'dropdown_yes_no', 'dropdown_pass_fail'];
    protected $table = "zunit_test_2s";
    public $menuTitle = "UT02 (Dropdown/Checkbox)";


    public $eloquentParams = [
        "radioYesNo" => ['belongsTo', Qaqc_insp_control_value::class, 'radioYesNo'],
        "radioPassFail" => ['belongsTo', Qaqc_insp_control_value::class, 'radioPassFail'],
        "dropdownYesNo" => ['belongsTo', Qaqc_insp_control_value::class, 'dropdownYesNo'],
        "dropdownPassFail" => ['belongsTo', Qaqc_insp_control_value::class, 'dropdownPassFail'],
    ];

    public $oracyParams = [
        // "radioYesNo" => ["getCheckedByField", Qaqc_insp_control_value::class],
        // "radioPassFail" => ["getCheckedByField", Qaqc_insp_control_value::class],
        // "dropdownYesNo" => ["getCheckedByField", Qaqc_insp_control_value::class],
        // "dropdownPassFail" => ["getCheckedByField", Qaqc_insp_control_value::class],
        "checkboxYesNo" => ["getCheckedByField", Qaqc_insp_control_value::class],
        "checkboxPassFail" => ["getCheckedByField", Qaqc_insp_control_value::class],
        "dropdownMultiYesNo" => ["getCheckedByField", Qaqc_insp_control_value::class],
        "dropdownMultiPassFail" => ["getCheckedByField", Qaqc_insp_control_value::class],
    ];

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
        $p = $this->eloquentParams[__FUNCTION__ . "()"];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
    public function checkboxPassFail()
    {
        $p = $this->eloquentParams[__FUNCTION__ . "()"];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
    public function dropdownMultiYesNo()
    {
        $p = $this->eloquentParams[__FUNCTION__ . "()"];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
    public function dropdownMultiPassFail()
    {
        $p = $this->eloquentParams[__FUNCTION__ . "()"];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
}
