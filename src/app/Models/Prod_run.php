<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_run extends ModelExtended
{
    protected $fillable = [
        "id", "prod_sequence_id", "date", "start", "end", "owner_id",
        "total_hours", "total_man_hours",
        "worker_number", "worker_number_count", "worker_number_input",
    ];

    protected $table = 'prod_runs';
    public static $nameless = true;
    protected static $statusless = true;

    public static $eloquentParams = [
        "getProdSequence" => ['belongsTo', Prod_sequence::class, 'prod_sequence_id'],
        "getUsers" => ['belongsToMany', User::class, 'prod_user_runs', 'prod_run_id', 'user_id'],
    ];

    public static $oracyParams = [
        'getWorkersOfRun()' => ['getCheckedByField', User::class,],
    ];

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('user_id');
    }

    public function getProdSequence()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWorkersOfRun()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', 'invisible' => true,],
            ['dataIndex' => 'prod_sequence_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'date', 'cloneable' => true, 'footer' => 'agg_none'],
            ['dataIndex' => 'start', 'cloneable' => true,],
            ['dataIndex' => 'end', 'cloneable' => true,],
            ['dataIndex' => 'total_hours', 'footer' => 'agg_sum', 'no_print' => true,],
            ['dataIndex' => 'worker_number_input', 'footer' => 'agg_sum',],
            ['dataIndex' => 'getWorkersOfRun()', 'cloneable' => true, 'no_print' => true,],
            ['dataIndex' => 'worker_number_count', 'invisible' => true,],
            ['dataIndex' => 'worker_number',  'footer' => 'agg_avg', 'no_print' => true,],
            ['dataIndex' => 'total_man_hours', 'footer' => 'agg_sum', 'no_print' => true,],
        ];
    }
}
