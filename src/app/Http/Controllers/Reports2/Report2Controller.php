<?php

namespace App\Http\Controllers\Reports2;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowReport;
use App\Models\Rp_report;
use Illuminate\Http\Request;

class Report2Controller extends Controller
{
    use TraitEntityCRUDShowReport;

    private $type;

    function getType()
    {
        return $this->type;
    }

    function index(Request $request, $report_slug)
    {
        $report = Rp_report::query()->whereRaw("BINARY LOWER(REPLACE(name, ' ', '-')) = ?", [$report_slug])->get()->first();
        if ($report == null) abort("404", "Report $report_slug not found");

        $this->type = $report->entity_type;

        return $this->showReport($request, $report->id);
    }
}
