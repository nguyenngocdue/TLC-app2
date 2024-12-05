<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_page extends ModelExtended
{
    protected $fillable = [
        "id", "name", "title", "report_id", "iterator_block_id", "is_active", "letter_head_id", "letter_footer_id",
        "is_landscape", "width", "height", "background",
        "is_stackable_letter_head", "is_full_width", "page_body_class", "order_no", "owner_id"
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getParent" => ['belongsTo', Rp_report::class, 'report_id'],
        "getLetterHead" => ['belongsTo', Rp_letter_head::class, 'letter_head_id'],
        "getLetterFooter" => ['belongsTo', Rp_letter_footer::class, 'letter_footer_id'],

        "getIteratorBlock" => ['belongsTo', Rp_block::class, 'iterator_block_id'],

        "getBlockDetails" => ["hasMany", Rp_page_block_detail::class, "rp_page_id"],

        "attachment_background" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getLetterHead()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getLetterFooter()
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

    public function getBlockDetails()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getIteratorBlock()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'id', /* 'invisible' => true, */],
            ["dataIndex" => 'order_no',/*  'invisible' => true, */],
            ["dataIndex" => 'report_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ["dataIndex" => 'iterator_block_id'],
            ["dataIndex" => 'is_active', 'cloneable' => true,],
            ["dataIndex" => 'name'],
            ["dataIndex" => 'letter_head_id'],
            ["dataIndex" => 'letter_footer_id'],
            ["dataIndex" => 'is_landscape'],
            ["dataIndex" => 'width'],
            ["dataIndex" => 'height'],
            ["dataIndex" => 'is_stackable_letter_head'],
            ["dataIndex" => 'is_full_width'],
            ["dataIndex" => 'page_body_class'],
            ["dataIndex" => 'attachment_background'],
        ];
    }

    public function getManyLineParamsLetterHead()
    {
        return [
            ["dataIndex" => 'id'],
            ["dataIndex" => 'order_no', 'invisible' => true,],
            ["dataIndex" => 'letter_head_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ["dataIndex" => 'name'],
        ];
    }

    public function getManyLineParamsLetterFooter()
    {
        return [
            ["dataIndex" => 'id'],
            ["dataIndex" => 'order_no', 'invisible' => true,],
            ["dataIndex" => 'letter_footer_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ["dataIndex" => 'name'],
        ];
    }
}
