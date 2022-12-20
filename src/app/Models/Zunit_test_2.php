<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_2 extends ModelExtended
{
    protected $fillable = ['content', 'radio_yes_no', 'radio_pass_fail', 'dropdown_yes_no', 'dropdown_pass_fail'];
    protected $table = "zunit_test_2s";
    public $menuTitle = "UT02 (Dropdown/Checkbox)";


    public $eloquentParams = [
        "radioYesNo" => ['belongsTo', Qaqc_insp_control_value::class, 'radio_yes_no'],
        "radioPassFail" => ['belongsTo', Qaqc_insp_control_value::class, 'radio_pass_fail'],
        "dropdownYesNo" => ['belongsTo', Qaqc_insp_control_value::class, 'dropdown_yes_no'],
        "dropdownPassFail" => ['belongsTo', Qaqc_insp_control_value::class, 'dropdown_pass_fail'],
        "checkboxYesNo" => ['belongsToMany', Qaqc_insp_control_value::class, 'zunit_test_2s_qaqc_insp_values_rel_1', 'zunit_test_2_id', 'qaqc_insp_value_id'],
        "checkboxPassFail" => ['belongsToMany', Qaqc_insp_control_value::class, 'zunit_test_2s_qaqc_insp_values_rel_2', 'zunit_test_2_id', 'qaqc_insp_value_id'],
        "dropdownMultiYesNo" => ['belongsToMany', Qaqc_insp_control_value::class, 'zunit_test_2s_qaqc_insp_values_rel_3', 'zunit_test_2_id', 'qaqc_insp_value_id'],
        "dropdownMultiPassFail" => ['belongsToMany', Qaqc_insp_control_value::class, 'zunit_test_2s_qaqc_insp_values_rel_4', 'zunit_test_2_id', 'qaqc_insp_value_id'],

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
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
    public function checkboxPassFail()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
    public function dropdownMultiYesNo()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
    public function dropdownMultiPassFail()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
}
