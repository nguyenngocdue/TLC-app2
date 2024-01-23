<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Department;
use App\View\Components\Reports\ParentParamReports;
use App\View\Components\Reports\ParentTypeParamReport;
use Illuminate\Support\Facades\DB;

class ParamDepartmentId extends ParentParamReports
{
    protected function getDataSource()
    {
        $hasListenTo = $this->hasListenTo();
        $sql = "SELECT 
                        dep.id AS id
                        ,dep.description
                        ,dep.name AS name
                        ";
        if ($hasListenTo) $sql .= " ,us.id AS $this->referData";
        $sql .= "\n FROM  departments dep";
        if ($hasListenTo) $sql .= ", users us";
        $sql .= "\n WHERE 1 = 1 AND dep.deleted_at IS NULL";
        if ($hasListenTo) $sql .= "\n AND us.department = dep.id";
        $sql .=  "\n ORDER BY dep.name";
        $result = DB::select($sql);
        return $result;
    }
}
