<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Http\Traits\HasCheckbox;

class Zunit_test_1 extends ModelExtended
{
    use HasCheckbox;
    protected $fillable = ["text1", "text2", "text3", "dropdown1", "radio1", "boolean1"];
    protected $primaryKey = 'id';
    protected $table = 'zunit_test_1s';
    public $menuTitle = "UT01 (Basic Controls)";

    public $eloquentParams = [
        "workplaceDropDown1" => ['belongsTo', Workplace::class, 'dropdown1'],
        "workplaceRadio1" => ['belongsTo', Workplace::class, 'radio1'],
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


    public $oracyParams = [
        "newCheckbox()" => ["getCheckedByField", Workplace::class],
        "newDropdownMulti()" => ["getCheckedByField", Workplace::class],
    ];

    public function newCheckbox()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function newDropdownMulti()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
