<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_run_line extends ModelExtended
{
    protected $fillable = ["prod_run_id", "date", "start", "end"];
    protected $primaryKey = 'id';
    protected $table = 'prod_run_lines';
    public $timestamps = true;

    public $eloquentParams = [
        "users" => ['belongsToMany', User::class, 'prod_user_runs', 'prod_run_line_id', 'user_id'],
        "prodRun" => ['belongsTo', Prod_run::class, 'prod_run_id'],
    ];

    public function users()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('user_id');
    }

    public function prodRun()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', 'renderer' => 'id', 'type' => 'prod_run_lines', 'align' => 'center'],
            ['dataIndex' => 'prodRun', 'title' => 'Run ID', 'renderer' => 'column', 'rendererParam' => 'id'],
            // ['dataIndex' => 'prodRun',],
            ['dataIndex' => 'date',],
            ['dataIndex' => 'start',],
            ['dataIndex' => 'end',],
        ];
    }
}
