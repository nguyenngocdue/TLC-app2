<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_report extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "entity_type", "owner_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getPages" => ["hasMany", Rp_page::class, "report_id"],
        "getFilterDetails" => ["hasMany", Rp_report_filter_detail::class, "rp_report_id"],

        "getFilterModes" => ["hasMany", Rp_filter_mode::class, "report_id"],
    ];

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
    public function getFilterDetails()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

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
            ->get()
            ->first();
        // $rpReport->getPages[10]->getBlockDetails;
        // dd($rpReport->getPages[10]);
        return $rpReport;
    }
}
