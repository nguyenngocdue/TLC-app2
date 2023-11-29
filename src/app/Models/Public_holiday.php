<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Public_holiday extends ModelExtended
{
    protected $fillable = ['id', 'name', 'year', 'workplace_id', 'ph_date', 'ph_hours', 'owner_id'];
    public static $statusless = true;

    public static $eloquentParams = [
        "getWorkplace" => ['belongsTo', Workplace::class, 'workplace_id'],
    ];

    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'id',],
            ['dataIndex' => 'workplace_id', 'title' => 'Workplace',],
            ['dataIndex' => 'year'],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'ph_date'],
            ['dataIndex' => 'ph_hours'],
        ];
    }
}
