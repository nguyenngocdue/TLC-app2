<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Utils\Support\DateTimeConcern;

class Esg_sheet extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "status", "workplace_id",
        "esg_month", "owner_id", "esg_tmpl_id", "total",
    ];

    public static $nameless = true;
    public function getNameAttribute($value)
    {
        $template = $this->getEsgTmpl;
        return ($template->name ?? "Template?") . " - " . DateTimeConcern::convertForLoading("picker_month", $this->esg_month);
    }

    public static $eloquentParams = [
        "getEsgTmpl" => ["belongsTo", Esg_tmpl::class, "esg_tmpl_id"],
        "getLines" => ["hasMany", Esg_sheet_line::class, "esg_sheet_id"],
        'getWorkplace' => ['belongsTo', Workplace::class, 'workplace_id'],
    ];

    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getEsgTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
