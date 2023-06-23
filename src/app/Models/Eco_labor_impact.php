<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Eco_labor_impact extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "remark", "eco_sheet_id", "discipline_id", "head_count", "man_day",
        "labor_cost", "total_cost",
    ];
    protected $table = "eco_labor_impacts";
    protected static $statusless = true;

    public $eloquentParams = [];

    public $oracyParams = [];


    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'remark',],
            ['dataIndex' => 'eco_sheet_id',],
            ['dataIndex' => 'discipline_id',],
            ['dataIndex' => 'head_count',],
            ['dataIndex' => 'man_day',],
            ['dataIndex' => 'labor_cost',],
            ['dataIndex' => 'total_cost',],
        ];
    }
}
