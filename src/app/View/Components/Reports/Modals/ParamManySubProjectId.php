<?php

namespace App\View\Components\Reports\Modals;

use App\View\Components\Reports\ParentIdParamReports2;
use Illuminate\Support\Facades\DB;

class ParamManySubProjectId extends ParentIdParamReports2
{
    protected $referData = 'project_id';
    protected function getDataSource($attr_name)
    {
        $sql = "SELECT 
                        sp.id AS id
                        ,sp.description
                        ,sp.name AS name
                        ,sp.status AS sp_status
                        ,sp.project_id AS $attr_name
                        FROM sub_projects sp
                        WHERE sp.deleted_at IS NULL
                        ORDER BY sp.name
                    ";
        $result = DB::select($sql);
        return $result;
    }
}
