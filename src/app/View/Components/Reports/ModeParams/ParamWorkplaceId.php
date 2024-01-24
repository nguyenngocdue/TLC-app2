<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamWorkplaceId extends ParentParamReports
{
    // protected $referData = 'user_id';
    public function getDataSource()
    {
        $hasListenTo = $this->hasListenTo();
        $sql = "SELECT 
                        wp.id AS id
                        ,wp.description
                        ,wp.name AS name";
        if ($hasListenTo) $sql .= " ,us.id AS $this->referData";
        $sql .= "\n FROM  workplaces wp";
        if ($hasListenTo) $sql .= ", users us";
        $sql .= "\n WHERE 1 = 1 AND wp.deleted_at IS NULL";
        if ($hasListenTo) $sql .= "\n AND us.workplace = wp.id";
        $sql .=  "\n ORDER BY wp.name";
        $result = DB::select($sql);
        return $result;
    }
}
