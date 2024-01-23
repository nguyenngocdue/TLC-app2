<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Department;
use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamKanbanTaskNameId extends ParentParamReports
{
    protected function getDataSource()
    {
        $sql = "SELECT 
                        kbt.id AS id
                        ,kbt.description
                        ,CONCAT(kbt.name, ' (#', kbt.id, ')') AS name";
        $sql .= "\n FROM  kanban_tasks kbt";
        $sql .= "\n WHERE 1 = 1 AND kbt.deleted_at IS NULL";
        $sql .=  "\n ORDER BY kbt.name";
        $result = DB::select($sql);
        // dd($sql);
        return $result;
    }
}
