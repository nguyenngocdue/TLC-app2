<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Eco_material_impact_add extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "eco_sheet_id", "unit_price", "quantity",
        "unit_id", "amount", "order_no", "owner_id"
    ];
    protected $table = "eco_material_impact_adds";
    protected static $statusless = true;

    public $eloquentParams = [
        "getEcoSheet" => ['belongsTo', Eco_sheet::class, 'eco_sheet_id'],
        "getUnit" => ['belongsTo', Term::class, 'unit_id']
    ];

    public $oracyParams = [];

    public function getEcoSheet()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUnit()
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
            ['dataIndex' => 'name'],
            ['dataIndex' => 'unit_price',],
            ['dataIndex' => 'quantity',],
            ['dataIndex' => 'unit_id'],
            ['dataIndex' => 'amount',],
            ['dataIndex' => 'description'],
        ];
    }
}
