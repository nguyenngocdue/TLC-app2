<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_job_line extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'status', 'owner_id',  "order_no",
        "job_id", "phase_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getParent" => ["belongsTo", Pj_job::class, "job_id"],
        "getPhase" => ["belongsTo", Pj_job_phase::class, "phase_id"],
        "getSubLines" => ["hasMany", Pj_job_sub_line::class, "job_line_id"],
    ];

    public static $oracyParams = [];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPhase()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', 'invisible' => true,],
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'job_id', 'title' => 'ID', 'invisible' => true, 'value_as_parent_id' => true],

            ['dataIndex' => 'phase_id',],
            ['dataIndex' => 'name', 'title' => 'Name'],
            ['dataIndex' => 'description', 'title' => 'Description'],
            ['dataIndex' => 'getSubLines', 'renderer' => 'renderAllChildrenAndClickToOpenChildren'],

        ];
    }
}
