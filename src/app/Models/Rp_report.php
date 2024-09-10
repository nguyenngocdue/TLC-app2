<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_report extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "title",
        "description",
        "entity_type",
        "has_time_range",
        "owner_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getPages" => ["hasMany", Rp_page::class, "report_id"],
        "getRpFilters" => ["hasMany", Rp_filter::class, "report_id"],

        "getRpFilterLinks" => ["hasMany", Rp_filter_link::class, "report_id"],

    ];

    public function getDeep()
    {
        $rpReport = Rp_report::query()
            ->where('id', $this->id)
            ->with(["getPages" => function ($q) {
                $q->with("getLetterHead")
                    ->with('attachment_background')
                    ->with("getLetterFooter")
                    ->with(["getBlockDetails" => function ($q1) {
                        $q1->with(["attachment_background"])
                            ->with(["getBlock" => function ($q2) {
                                $q2->with('getLines');
                                $q2->with(['get2ndHeaderLines' => function ($q3) {
                                    $q3->with('getParent');
                                }]);
                            }]);
                    }]);
            }])
            ->with("getRpFilterLinks")
            ->get()
            ->first();
        // dd($rpReport);
        return $rpReport;
    }

    public function getRpFilterLinks()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPages()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getRpFilters()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
