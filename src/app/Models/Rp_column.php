<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_column extends ModelExtended
{
    protected $fillable = [
        "table_id", "parent_id", "block_id", "is_active", "data_index",
        "order_no", "name", "width", "cell_div_class_agg_footer", "cell_div_class",
        "cell_class", "icon", "icon_position", "row_cell_div_class", "row_cell_class",
        "row_icon", "row_icon_position", "row_href_fn", "row_renderer", "agg_footer",
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
}
