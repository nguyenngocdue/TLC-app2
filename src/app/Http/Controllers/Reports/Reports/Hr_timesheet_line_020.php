<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Workflow\LibPivotTables;


class Hr_timesheet_line_020 extends Hr_timesheet_line_100
{
    protected $libPivotFilters;
    protected $mode = '020';
    
    public function __construct()
    {
        $this->libPivotFilters = $this->getAttrFilters();
    }

    protected function getAttrFilters()
    {
        return LibPivotTables::getFor("hr_timesheet_project_date");;
    }

}
