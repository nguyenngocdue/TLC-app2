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
        "owner_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getPages" => ["hasMany", Rp_page::class, "report_id"],
        "getAdvancedFilters" => ["hasMany", Rp_advanced_filter::class, "report_id"],

        "getFilterLinkDetails" => ["hasMany", Rp_report_filter_link_detail::class, "rp_report_id"],

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
            ->with(["getFilterLinkDetails" => function ($q4) {
                $q4->with('getFilterLink');
            }])
            ->with("getAdvancedFilters")
            ->get()
            ->first();
        // $rpReport->getPages[10]->getBlockDetails;
        // dd($rpReport);
        return $rpReport;
    }

    public function getFilterLinkDetails()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPages()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getFilterModes()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAdvancedFilters()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
