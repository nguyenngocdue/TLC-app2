<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ghg_tmpl_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "owner_id", "order_no",
        "ghg_tmpl_id",
    ];
    protected $table = "ghg_tmpl_lines";

    public static $eloquentParams = [
        "getGhgTmpl" => ['belongsTo', Ghg_tmpl::class, 'ghg_tmpl_id'],
    ];

    public function getGhgTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'ghg_tmpl_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'description',],
        ];
    }
}
