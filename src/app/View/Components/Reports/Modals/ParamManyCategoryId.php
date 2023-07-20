<?php

namespace App\View\Components\Reports\Modals;

use App\View\Components\Reports\ParentIdParamReports2;
use Illuminate\Support\Facades\DB;

class ParamManyCategoryId extends ParentIdParamReports2
{
    protected $referData = 'user_id';
    protected function getDataSource($attr_name)
    {
        $sql = "SELECT 
                        uca.id AS id
                        ,uca.description
                        ,uca.name AS name
                        FROM  user_categories uca
                        WHERE uca.deleted_at IS NULL
                        ORDER BY uca.name
                    ";
        $result = DB::select($sql);
        return $result;
    }
}
