<?php

namespace App\Http\Controllers\Reports2;

use App\Http\Controllers\Controller;
use App\Models\Rp_page;
use App\Models\Rp_report;
use App\Utils\Support\Report;

class Rp_reportController extends Controller
{

    public function getData($reportId, $keys)
    {
        $rpPage = new Rp_report();
        $data = $rpPage->where('id', $reportId)->get()->toArray();
        return Report::getItemsByKeys($data, $keys);
    }
}
