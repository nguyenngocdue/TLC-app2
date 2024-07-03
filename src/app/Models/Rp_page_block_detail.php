<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_page_block_detail extends ModelExtended
{
    protected $fillable = [
        "id", "rp_block_id", "rp_page_id", "col_span",
        "order_no", "owner_id"
    ];

    public static $eloquentParams = [
        "getBlock" => ['belongsTo', Rp_Block::class, 'rp_block_id'],
        "getPage" => ['belongsTo', Rp_Page::class, 'rp_page_id'],

    ];

    public function getBlock()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPage()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParamsPages()
    {
        return [
            ["dataIndex" => 'id'],
            ["dataIndex" => 'order_no', 'invisible' => true,],
            ["dataIndex" => 'rp_page_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ["dataIndex" => 'rp_block_id'],
            ["dataIndex" => 'col_span'],
        ];
    }
}
