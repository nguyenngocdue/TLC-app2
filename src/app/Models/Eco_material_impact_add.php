<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Eco_material_impact_add extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "eco_sheet_id", "unit_price", "quantity",
        "unit_id", "amount", "order_no", "owner_id"
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "getEcoSheet" => ['belongsTo', Eco_sheet::class, 'eco_sheet_id'],
        "getUnit" => ['belongsTo', Term::class, 'unit_id']
    ];

    public function getEcoSheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUnit()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'eco_sheet_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'unit_price', 'cloneable' => true,],
            ['dataIndex' => 'quantity', 'cloneable' => true,],
            ['dataIndex' => 'unit_id', 'cloneable' => true,],
            ['dataIndex' => 'amount', 'footer' => 'agg_sum'],
            ['dataIndex' => 'description'],
        ];
    }
}
