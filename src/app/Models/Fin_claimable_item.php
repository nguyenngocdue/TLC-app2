<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Fin_claimable_item extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "title",
        "description",
        "owner_id",
        "order_no",

        "claimable_type",
        "claimable_id",
    ];

    public static $eloquentParams = [
        "claimable" => ['morphTo', Fin_claimable_item::class, 'claimable_type', 'claimable_id'],
    ];

    public function claimable()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'claimable_type', 'title' => 'Parent Type', 'invisible' => true, 'value_as_parent_type' => true],
            ['dataIndex' => 'claimable_id', 'title' => 'Parent ID', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'title'],
        ];
    }
}
