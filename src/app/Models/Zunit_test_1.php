<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_1 extends ModelExtended
{
    protected $fillable = ["text1", "text2", "text3", "text4", "dropdown1", "radio1", "boolean1"];
    protected $primaryKey = 'id';
    protected $table = 'zunit_test_1s';

    public $eloquentParams = [
        "workplaceDropDown1" => ['belongsTo', Workplace::class, 'dropdown1'],
        "workplaceRadio1" => ['belongsTo', Workplace::class, 'radio1'],
    ];

    public $oracyParams = [
        "checkboxZut1()" => ["getCheckedByField", Workplace::class],
        "dropdownMultiZut1()" => ["getCheckedByField", Workplace::class],
    ];

    public function workplaceDropDown1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function workplaceRadio1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function checkboxZut1()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function dropdownMultiZut1()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
