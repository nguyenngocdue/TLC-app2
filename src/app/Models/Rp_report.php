<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_report extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "advanced_filter_col_span", "owner_id",
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

    // public function get()
    // {
    //     $rpReport = Rp_report::query()
    //         ->where('id', $this->id)
    //         ->with(["getPages" => function ($q) {
    //             $q->with("getLetterHead")
    //                 ->with('attachment_background')
    //                 ->with("getLetterFooter")
    //                 ->with(["getBlockDetails" => function ($q) {
    //                     $q->with("getBlock")
    //                         ->with("attachment_background");
    //                 }]);
    //         }])
    //         ->get()
    //         ->first();
    //     // $rpReport->getPages[29]->getBlockDetails;
    //     return $rpReport;
    // }

    public function get()
    {
        $reports = Rp_report::with([
            'getPages' => function ($query) {
                $query->with([
                    'getLetterHead',
                    'attachment_background',
                    'getLetterFooter',
                    'getBlockDetails' => function ($query) {
                        $query->with(['getBlock', 'attachment_background']);
                    }
                ]);
            }
        ])->get()->keyBy('id')->toArray();
        return $reports;
    }
}
