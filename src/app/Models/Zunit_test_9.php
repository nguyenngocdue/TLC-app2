<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_9 extends ModelExtended
{
    protected $fillable = [];
    protected $table = "zunit_test_9s";
    public $menuTitle = "UT09 (Basic Event)";

    public $eloquentParams = [
        "workplaceDropDown1" => ['belongsTo', Workplace::class, 'department_1'],
        "workplaceRadio1" => ['belongsTo', Workplace::class, 'department_2'],
        "category" => ['belongsTo', Workplace::class, 'category'],
        "head_of_department" => ['belongsTo', Workplace::class, 'head_of_department'],
        "user" => ['belongsTo', Workplace::class, 'user']
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
    public function category()

    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function head_of_department()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
