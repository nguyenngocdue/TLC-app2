<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_run extends ModelExtended
{
    protected $fillable = [
        "id", "prod_sequence_id", "date", "start", "end", "total_hours", "owner_id",
        "worker_number", "total_man_hours"
    ];

    protected $table = 'prod_runs';
    public static $nameless = true;
    protected static $statusless = true;

    public static $eloquentParams = [
        "getProdSequence" => ['belongsTo', Prod_sequence::class, 'prod_sequence_id'],
        "getUsers" => ['belongsToMany', User::class, 'prod_user_runs', 'prod_run_id', 'user_id'],
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

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', 'invisible' => true,],
            ['dataIndex' => 'prod_sequence_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'date', 'cloneable' => true,],
            ['dataIndex' => 'start', 'cloneable' => true,],
            ['dataIndex' => 'end', 'cloneable' => true,],
            ['dataIndex' => 'total_hours', 'footer' => 'agg_sum'],
            ['dataIndex' => 'worker_number', 'cloneable' => true,],
            ['dataIndex' => 'total_man_hours', 'footer' => 'agg_sum'],
            // ['dataIndex' => 'owner_id',],
        ];
    }
}
