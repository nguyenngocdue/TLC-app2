<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_block extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "sql_string", "owner_id",
        "table_true_width", "max_h", "showNo",
        "rotate_45_width", "rotate_45_height", "renderer_type", "chart_json", "has_pagination",
        "top_left_control", "top_center_control", "top_right_control",
        "bottom_left_control", "bottom_center_control", "bottom_right_control",
        "chart_type", "html_content",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        'getChartType' => ['belongsTo', Term::class, 'chart_type'],
        'getRendererType' => ['belongsTo', Term::class, 'renderer_type'],
        'getControl' => ['belongsTo', Term::class, 'top_left_control'],
        'getControl2' => ['belongsTo', Term::class, 'top_center_control'],
        'getControl3' => ['belongsTo', Term::class, 'top_right_control'],
        'getControl4' => ['belongsTo', Term::class, 'bottom_left_control'],
        'getControl5' => ['belongsTo', Term::class, 'bottom_center_control'],
        'getControl6' => ['belongsTo', Term::class, 'bottom_right_control'],

        "getLines" => ["hasMany", Rp_column::class, "block_id"],
        "get2ndHeaderLines" => ["hasMany", Rp_column::class, "block_id"],

        "html_attachment" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public function getChartType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getRendererType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getControl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getControl2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getControl3()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getControl4()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControl5()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControl6()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function html_attachment()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2])->whereNull('parent_id');
    }
    public function get2ndHeaderLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2])->whereNotNull('parent_id');
    }
}
