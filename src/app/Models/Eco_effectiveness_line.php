<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Eco_effectiveness_line extends ModelExtended
{
    protected $fillable = [
        "id", "description", "remark", "eco_sheet_id", "change_effectiveness_id", "order_no", "owner_id"
    ];
    public static $statusless = true;
    public static $nameless = true;

    public static $eloquentParams = [
        "getEcoSheet" => ['belongsTo', Eco_sheet::class, 'eco_sheet_id'],
        "getChangeEffectiveness" => ['belongsTo', Term::class, 'change_effectiveness_id']
    ];

    public static $oracyParams = [];
    public function getEcoSheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getChangeEffectiveness()
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
            ['dataIndex' => 'change_effectiveness_id'],
            ['dataIndex' => 'description'],
        ];
    }
}
