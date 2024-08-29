<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_column extends ModelExtended
{
    protected $fillable = [
        "id", "name", "title", "block_id", "parent_id", "is_active", "data_index",
        "col_span", "row_span",
        "order_no", "name", "width", "cell_div_class_agg_footer", "cell_div_class",
        "cell_class", "icon", "icon_position", "row_cell_div_class", "row_cell_class",
        "row_icon", "row_icon_position", "row_href_fn", "row_renderer","entity_type", "agg_footer",
        "owner_id"
    ];

    public static $eloquentParams = [
        'getIconPosition' => ['belongsTo', Term::class, 'icon_position'],
        'getRowIconPosition' => ['belongsTo', Term::class, 'row_icon_position'],
        'getRowRenderer' => ['belongsTo', Term::class, 'row_renderer'],
        'getAggFooter' => ['belongsTo', Term::class, 'agg_footer'],
        "getBlock" => ['belongsTo', Rp_block::class, 'block_id'],
        "getParent" => ['belongsTo', Rp_column::class, 'parent_id'],
    ];

    public function getIconPosition()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getRowIconPosition()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getRowRenderer()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getBlock()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAggFooter()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'id'],
            ["dataIndex" => 'order_no', 'invisible' => true,],
            ["dataIndex" => 'block_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ["dataIndex" => 'title'],
            ["dataIndex" => 'name'],
            ["dataIndex" => 'is_active'],
            ["dataIndex" => 'data_index'],
            ["dataIndex" => 'col_span'],
            ["dataIndex" => 'row_span'],
            ["dataIndex" => 'width'],
            ["dataIndex" => 'cell_class'],
            ["dataIndex" => 'cell_div_class'],
            ["dataIndex" => 'icon'],
            ["dataIndex" => 'icon_position'],
            ["dataIndex" => 'row_cell_div_class'],
            ["dataIndex" => 'row_cell_class'],
            ["dataIndex" => 'row_icon'],
            ["dataIndex" => 'row_icon_position'],
            ["dataIndex" => 'row_href_fn'],
            ["dataIndex" => 'row_renderer'],
            ["dataIndex" => 'entity_type'], // for status
            ["dataIndex" => 'agg_footer'],
            ["dataIndex" => 'cell_div_class_agg_footer'],
        ];
    }
    public function getManyLineParams_2nd()
    {
        return [
            ["dataIndex" => 'id'],
            ["dataIndex" => 'order_no', 'invisible' => true,],
            ["dataIndex" => 'block_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ["dataIndex" => 'parent_id',],
            ["dataIndex" => 'name'],
            // ["dataIndex" => 'is_active'],
            // ["dataIndex" => 'data_index'],
            // ["dataIndex" => 'width'],
            ["dataIndex" => 'cell_div_class'],
            ["dataIndex" => 'cell_class'],
            ["dataIndex" => 'icon'],
            ["dataIndex" => 'icon_position'],
            // ["dataIndex" => 'row_cell_div_class'],
            // ["dataIndex" => 'row_cell_class'],
            // ["dataIndex" => 'row_icon'],
            // ["dataIndex" => 'row_icon_position'],
            // ["dataIndex" => 'row_href_fn'],
            // ["dataIndex" => 'row_renderer'],
            // ["dataIndex" => 'agg_footer'],
            // ["dataIndex" => 'cell_div_class_agg_footer'],
        ];
    }
}
