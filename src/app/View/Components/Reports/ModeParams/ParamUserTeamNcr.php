<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Project;
use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamUserTeamNcr extends ParentParamReports
{
    protected function getDataSource()
    {
        $sql = "SELECT 
                    ustncr.id AS id
                    ,ustncr.name AS name
                    ,ustncr.description AS description
                    FROM user_team_ncrs ustncr
                        WHERE ustncr.deleted_at IS NULL
                        ORDER BY ustncr.name";
        $result = DB::select($sql);
        return $result;
    }
}
