<?php

namespace App\View\Components\Reports\Modals;

use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamCategoryId extends ParentParamReports
{
    protected $referData = '';
    protected function getDataSource()
    {
        $hasListenTo = $this->hasListenTo();
        $sql = "SELECT 
                        uca.id AS id
                        ,uca.description
                        ,uca.name AS name";
        if ($hasListenTo) $sql .= " ,us.id AS $this->referData";
        $sql .= "\n FROM  user_categories uca";
        if ($hasListenTo) $sql .= ", users us";
        $sql .= "\n WHERE 1 = 1 AND uca.deleted_at IS NULL";
        if ($hasListenTo) $sql .= "\n AND us.category = uca.id";
        $sql .=  "\n ORDER BY uca.name";
        $result = DB::select($sql);
        return $result;
    }
}
