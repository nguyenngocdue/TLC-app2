<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamKanbanTaskGroup extends ParentParamReports
{
    protected function getDataSource()
    {
        $sql = "SELECT ktg.id AS id, CONCAT(ktg.name, ' (#', ktg.id, ')') AS name 
                    FROM kanban_task_groups ktg 
                    WHERE ktg.deleted_by IS NULL";
        $result = DB::select($sql);
        return $result;
    }
}
