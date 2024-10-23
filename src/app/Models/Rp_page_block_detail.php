<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_page_block_detail extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "rp_block_id",
        "rp_page_id",
        "is_active",
        "col_span",
        "order_no",
        "owner_id"
    ];

    public static $statusless = true;
    public static $nameLess = true;

    public static $eloquentParams = [
        "getBlock" => ['belongsTo', Rp_block::class, 'rp_block_id'],
        "getPage" => ['belongsTo', Rp_page::class, 'rp_page_id'],

        "attachment_background" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],

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

    public function attachment_background()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'id', 'invisible' => !true,],
            ["dataIndex" => 'order_no', /* 'invisible' => true, */],
            ["dataIndex" => 'rp_page_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ["dataIndex" => 'rp_block_id', /*'read_only_rr2' => 1*/],
            ["dataIndex" => 'is_active', 'cloneable' => true,],
            ["dataIndex" => 'col_span'],
            ["dataIndex" => 'attachment_background'],
        ];
    }
}
