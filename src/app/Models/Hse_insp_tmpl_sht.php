<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_insp_tmpl_sht extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "owner_id", "order_no"];
    public static $statusless = true;

    public static $eloquentParams = [
        "getLines" => ["hasMany", Hse_insp_tmpl_line::class, "hse_insp_tmpl_sht_id"],
    ];

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ['dataIndex' => 'id',],
            ['dataIndex' => 'hse_insp_tmpl_id'],
            ['dataIndex' => 'name'],
            // ['dataIndex' => 'description'],
            ['dataIndex' => 'getLines', /*'rendererParam' => 'description'*/],
        ];
    }
}
