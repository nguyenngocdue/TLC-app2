<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_01 extends ModelExtended
{
    protected $fillable = ["name", "text1", "text2", "text3", "text4", "dropdown1", "radio1", "boolean1", "parent_id", "order_no"];
    protected $primaryKey = 'id';
    protected $table = 'zunit_test_01s';

    public $eloquentParams = [
        "workplaceDropDown1" => ['belongsTo', Workplace::class, 'dropdown1'],
        "workplaceRadio1" => ['belongsTo', Workplace::class, 'radio1'],
    ];

    public $oracyParams = [
        "checkboxZut1()" => ["getCheckedByField", Workplace::class],
        "dropdownMultiZut1()" => ["getCheckedByField", Workplace::class],
    ];

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'parent_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'text1'],
            ['dataIndex' => 'text2'],
            ['dataIndex' => 'text3'],
            ['dataIndex' => 'text4'],
            ['dataIndex' => 'dropdown1'],
            ['dataIndex' => 'radio1'],
            ['dataIndex' => 'boolean1'],
            ['dataIndex' => 'checkboxZut1()'],
            ['dataIndex' => 'dropdownMultiZut1()'],
        ];
    }

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
