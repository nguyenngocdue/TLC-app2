<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamKanbanTaskBucket extends ParentParamReports
{
    protected function getDataSource()
    {
        $sql = "SELECT ktb.id AS id, CONCAT(ktb.name, ' (#', ktb.id, ')') AS name 
                    FROM kanban_task_buckets ktb 
                    WHERE ktb.deleted_by IS NULL";
        $result = DB::select($sql);
        return $result;
    }
}
