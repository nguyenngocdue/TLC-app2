<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_induction extends ModelExtended
{
    protected $fillable = [
        "id", "owner_id", "name", "description", "the_month", "workplace_id",
        "total_trainees", "total_hours", "status",
    ];
    // public static $statusless = true;
    public static $nameless = true;
    public function getNameAttribute($value)
    {
        $the_month = $this->the_month;
        $wp = $this->getWorkplace;
        return ($wp ? $wp->name : "") . " " . ($the_month);
    }

    public static $eloquentParams = [
        'getWorkplace' => ['belongsTo', Workplace::class, 'workplace_id'],
        'getLines' => ['hasMany', Esg_induction_line::class, 'esg_induction_id'],
        "attachment_esg_induction" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function attachment_esg_induction()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}
