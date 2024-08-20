<?php

namespace App\Http\Controllers\Reports2;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UpdateUserSettings;
use App\Models\Rp_report;
use Illuminate\Http\Request;

class Rp_reportController extends Controller
{

   
    public function updateFilters(Request $request)
    {
        (new UpdateUserSettings())($request);
        return redirect()->back();
    }
}
