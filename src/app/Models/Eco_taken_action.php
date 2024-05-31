<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Eco_taken_action extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "eco_sheet_id", "department_id", "owner_id", "order_no"
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "getEcoSheet" => ['belongsTo', Eco_sheet::class, 'eco_sheet_id'],
        "getDepartment" => ['belongsTo', Department::class, 'department_id']
    ];

    public function getEcoSheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDepartment()
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
            ['dataIndex' => 'department_id'],
            ['dataIndex' => 'description'],
        ];
    }
}
