<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_01 extends ModelExtended
{
    protected $fillable = [
        'id',
        "name",
        "text1",
        "text2",
        "text3",
        "text4",
        "number1", //int
        "number2", //float
        "number3", //double
        "number4", //decimal(8,4)
        "number5", //decimal(10,6)
        "dropdown1",
        "radio1",
        "boolean1",
        "parent_id",
        "order_no",
        "signature",
        'owner_id',
    ];

    public static $statusless = true;

    protected $casts = [
        "text4" => "array"
    ];

    public static $eloquentParams = [
        "workplaceDropDown1" => ['belongsTo', Workplace::class, 'dropdown1'],
        "workplaceRadio1" => ['belongsTo', Workplace::class, 'radio1'],

        "workplaceDropdownMulti1" => ['belongsToMany', Workplace::class, 'ym2m_workplace_zunit_test_01'],
        "workplaceCheckbox1" => ['belongsToMany', Workplace::class, 'ym2m_workplace_zunit_test_01'],
    ];

    public static $oracyParams = [
        "checkboxZut1()" => ["getCheckedByField", Workplace::class],
        "dropdownMultiZut1()" => ["getCheckedByField", Workplace::class],
    ];

    public function workplaceDropdownMulti1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function workplaceCheckbox1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => !true],
            ['dataIndex' => 'id', 'invisible' => !true],
            ['dataIndex' => 'parent_id', 'invisible' => !true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name', 'cloneable' => true],
            ['dataIndex' => 'text1', 'cloneable' => true],
            ['dataIndex' => 'text2', 'cloneable' => true],
            // ['dataIndex' => 'text3'],
            ['dataIndex' => 'text4', 'cloneable' => true],

            ['dataIndex' => 'number1', 'cloneable' => true],
            ['dataIndex' => 'number2', 'cloneable' => true],
            ['dataIndex' => 'number3', 'cloneable' => true],
            ['dataIndex' => 'number4', 'cloneable' => true],
            ['dataIndex' => 'number5', 'cloneable' => true],

            // ['dataIndex' => 'dropdown1'],
            ['dataIndex' => 'radio1', 'cloneable' => true],
            ['dataIndex' => 'boolean1', 'cloneable' => true],
            ['dataIndex' => 'checkboxZut1()', 'cloneable' => true],
            // ['dataIndex' => 'dropdownMultiZut1()'],
        ];
    }

    public function workplaceDropDown1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function workplaceRadio1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function checkboxZut1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function dropdownMultiZut1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
