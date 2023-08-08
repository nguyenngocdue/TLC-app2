<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Utils\Support\DateTimeConcern;

class Ghg_sheet extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description",
        "month", "owner_id", "ghg_tmpl_id",
    ];
    protected $table = "ghg_sheets";

    public static $nameless = true;
    public function getNameAttribute($value)
    {
        $template = $this->getGhgTmpl;
        return ($template->name ?? "Template?") . " - " . DateTimeConcern::convertForLoading("picker_month", $this->month);
    }

    public static $eloquentParams = [
        "getGhgTmpl" => ["belongsTo", Ghg_tmpl::class, "ghg_tmpl_id"],
        "getLines" => ["hasMany", Ghg_sheet_line::class, "ghg_sheet_id"],
    ];

    public function getGhgTmpl()
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
