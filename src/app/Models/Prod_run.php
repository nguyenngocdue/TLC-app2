<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_run extends ModelExtended
{
    protected $fillable = ["prod_sequence_id", "date", "start", "end"];
    protected $primaryKey = 'id';
    protected $table = 'prod_runs';
    public $timestamps = true;

    public $eloquentParams = [
        "users" => ['belongsToMany', User::class, 'prod_user_runs', 'prod_sequence_id', 'user_id'],
        "prodSequence" => ['belongsTo', Prod_run::class, 'prod_sequence_id'],
    ];

    public function users()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('user_id');
    }

    public function prodSequence()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', 'renderer' => 'id', 'type' => 'prod_runs', 'align' => 'center'],
            ['dataIndex' => 'prodSequence', 'title' => 'Run ID', 'renderer' => 'column', 'rendererParam' => 'id'],
            // ['dataIndex' => 'prodRun',],
            ['dataIndex' => 'date',],
            ['dataIndex' => 'start',],
            ['dataIndex' => 'end',],
        ];
    }
}
