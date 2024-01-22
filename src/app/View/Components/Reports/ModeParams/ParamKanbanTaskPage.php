<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamKanbanTaskPage extends ParentParamReports
{
    protected function getDataSource()
    {
        $sql = "SELECT ktp.id AS id, ktp.name AS name 
                    FROM kanban_task_pages ktp 
                    WHERE ktp.deleted_by IS NULL";
        $result = DB::select($sql);
        return $result;
    }
}
