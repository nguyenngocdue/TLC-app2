<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_block extends ModelExtended
{
    protected $fillable = [
        "id", "name", "sql_string", "owner_id",
        "table_true_width", "max_h",
        "rotate_45_width", "rotate_45_height", "renderer_type", "chart_json", "has_pagination",
        "top_left_control", "top_center_control", "top_right_control",
        "bottom_left_control", "bottom_center_control", "bottom_right_control",
        "chart_type", "html_content",
    ];

    public static $eloquentParams = [
        'getChartType' => ['belongsTo', Term::class, 'chart_type'],
        "attachment_background" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        'getRendererType' => ['belongsTo', Term::class, 'renderer_type'],
        'getControl' => ['belongsTo', Term::class, 'top_left_control'],
        'getControl2' => ['belongsTo', Term::class, 'top_center_control'],
        'getControl3' => ['belongsTo', Term::class, 'top_right_control'],
        'getControl4' => ['belongsTo', Term::class, 'bottom_left_control'],
        'getControl5' => ['belongsTo', Term::class, 'bottom_center_control'],
        'getControl6' => ['belongsTo', Term::class, 'bottom_right_control'],

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

    public function attachment_background()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}
