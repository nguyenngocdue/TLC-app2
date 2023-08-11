<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ppr_run extends ModelExtended
{
    protected $fillable = [
        "id", "prod_sequence_id", "date", "start", "end", "owner_id",
        "total_hours",  "worker_number", "total_man_hours",
        "quantity",
    ];

    protected $table = 'ppr_runs';
    public static $nameless = true;
    protected static $statusless = true;

    public static $eloquentParams = [
        "getPprSequence" => ['belongsTo', Ppr_sequence::class, 'ppr_sequence_id'],
    ];

    public function getPprSequence()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', 'invisible' => true,],
            ['dataIndex' => 'ppr_sequence_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'date', 'cloneable' => true, 'footer' => 'agg_none'],
            ['dataIndex' => 'start', 'cloneable' => true,],
            ['dataIndex' => 'end', 'cloneable' => true,],
            ['dataIndex' => 'quantity', 'cloneable' => true,],
            ['dataIndex' => 'total_hours', 'footer' => 'agg_sum'],
            ['dataIndex' => 'worker_number', 'cloneable' => true, 'footer' => 'agg_avg'],
            ['dataIndex' => 'total_man_hours', 'footer' => 'agg_sum'],
            // ['dataIndex' => 'owner_id',],
        ];
    }
}
