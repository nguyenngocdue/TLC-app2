<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_induction_line extends ModelExtended
{
    protected $fillable = [
        "id", "owner_id", "name", "description", "esg_induction_id", "the_date",
        "trainee_type", "trainee_count", "hours", "total_hours", "remark", "status", "order_no",
    ];
    public static $statusless = true;
    public static $nameless = true;

    public static $eloquentParams = [
        "getParent" => ["belongsTo", Esg_induction::class, 'esg_induction_id'],
        "getTraineeType" => ['belongsTo', Term::class, 'trainee_type'],
    ];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTraineeType()
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
            ['dataIndex' => 'the_date',],
            ['dataIndex' => 'description',],
            ['dataIndex' => 'trainee_type',],
            ['dataIndex' => 'trainee_count', 'footer' => 'agg_sum'],

            ['dataIndex' => 'hours',],
            ['dataIndex' => 'total_hours', 'footer' => 'agg_sum'],
            ['dataIndex' => 'remark',],
        ];
    }
}
