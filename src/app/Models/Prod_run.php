<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_run extends ModelExtended
{
    protected $fillable = ["prod_sequence_id", "date", "start", "end", "owner_id"];

    protected $table = 'prod_runs';
    public $nameless = true;
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
            ['dataIndex' => 'id'],
            ['dataIndex' => 'prod_sequence_id', 'title' => 'Run ID',  'rendererParam' => 'id'],
            ['dataIndex' => 'date',],
            ['dataIndex' => 'start',],
            ['dataIndex' => 'end',],
            ['dataIndex' => 'owner_id',],
        ];
    }
}
