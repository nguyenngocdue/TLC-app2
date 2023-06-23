<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Eco_material_impact_remove extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "remark", "eco_sheet_id", "price", "quantity",
        "unit_id", "total_amount",
    ];
    protected $table = "eco_material_impact_removes";
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
            ['dataIndex' => 'price',],
            ['dataIndex' => 'quantity',],
            ['dataIndex' => 'unit_id',],
            ['dataIndex' => 'total_amount',],
        ];
    }
}
