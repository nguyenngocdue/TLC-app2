<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Eco_labor_impact extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "eco_sheet_id", "department_id", "head_count", "man_day",
        "labor_cost", "total_cost", "owner_id", "order_no"
    ];
    protected $table = "eco_labor_impacts";
    protected static $statusless = true;

    public $eloquentParams = [
        "getEcoSheet" => ['belongsTo', Eco_sheet::class, 'eco_sheet_id'],
        "getDepartment" => ['belongsTo', Department::class, 'department_id']
    ];

    public $oracyParams = [];

    public function getEcoSheet()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDepartment()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'eco_sheet_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'department_id'],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'head_count',],
            ['dataIndex' => 'man_day',],
            ['dataIndex' => 'labor_cost',],
            ['dataIndex' => 'total_cost',],
            ['dataIndex' => 'description'],
        ];
    }
}
