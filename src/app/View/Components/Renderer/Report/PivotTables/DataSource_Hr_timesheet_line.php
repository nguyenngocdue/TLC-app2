<?php

namespace App\View\Components\Renderer\Report\PivotTables;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\Reports\Hr_timesheet_line_100;
use Illuminate\Http\Request;

class DataSource_Hr_timesheet_line extends Controller
{

    protected $modeType = '';
    public function getType()
    {
        return "dashboard";
    }


    private function getDataSource1()
    {
        $primaryData = (new Hr_timesheet_line_100())->getDataSource([]);
        return $primaryData;
    }

    public function index(Request $request)
    {
        // dd($this->modeType,  $this->getDataSource1());
        $dataSource = collect(array_slice($this->getDataSource1()->toArray(),0, 1000));
        return view("reports.report-pivot", [
            'key' => $this->modeType,
            'dataSource' => $dataSource
        ]);
    }
}
