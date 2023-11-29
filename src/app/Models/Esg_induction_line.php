<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_induction_line extends ModelExtended
{
    protected $fillable = [
        "id", "owner_id",
        "name",
        "description",
        "esg_induction_id",
        "the_date",
        "trainee_type",
        "total_hours",
        "status", "order_no",
    ];
    // public static $statusless = true;
    public static $nameless = true;

    public static $eloquentParams = [
        "getParent" => ["belongsTo", Esg_induction::class, 'esg_induction_id'],
    ];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'esg_induction_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'trainee_type',],
            ['dataIndex' => 'total_hours',],
        ];
    }
}
