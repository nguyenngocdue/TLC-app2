<?php

namespace App\View\Components\Reports\Modals;

use App\View\Components\Reports\ParentIdParamReports;
use Illuminate\Support\Facades\DB;

class ParamSubProjectId extends ParentIdParamReports
{
    protected $referData = 'project_id';
    protected function getDataSource($attr_name)
    {
        $sql = "SELECT 
                        sp.id AS id
                        ,sp.name AS name
                        ,sp.status AS sp_status
                        ,sp.project_id AS $attr_name
                        FROM sub_projects sp
                        WHERE sp.deleted_by IS NULL
                        ORDER BY sp.name
                    ";
        $result = DB::select($sql);
        return $result;
    }
}
