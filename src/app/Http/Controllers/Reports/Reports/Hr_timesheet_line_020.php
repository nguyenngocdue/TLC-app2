<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;

class Hr_timesheet_line_020 extends Controller
{

    protected function getTable()
    {
        $tableName = CurrentRoute::getCurrentController();
        $tableName = substr($tableName, 0, strrpos($tableName, "_"));
        $tableName = strtolower(Str::plural($tableName));
        return $tableName;
    }
    public function getType()
    {
        return $this->getTable();
        // return str_replace(' ', '_', strtolower($this->getMenuTitle()));
    }

    protected $key = 'hr_timesheet_project_date';
    private function getDataSource1()
    {
        $primaryData = (new Hr_timesheet_line_100())->getDataSource([]);
        $primaryData = array_map(fn($item) =>(array)$item, $primaryData->toArray());
        // dump($primaryData);
        return $primaryData;
    }


    public function index() {
        return view('reports.due-test-report', [
            'key' => $this->key,
            'dataSource' => $this->getDataSource1()
        ]);
    }
}
