<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamKanbanTaskCluster extends ParentParamReports
{
    protected function getDataSource()
    {
        $sql = "SELECT ktc.id AS id, ktc.name AS name 
                    FROM kanban_task_clusters ktc 
                    WHERE ktc.deleted_by IS NULL";
        $result = DB::select($sql);
        return $result;
    }
}
