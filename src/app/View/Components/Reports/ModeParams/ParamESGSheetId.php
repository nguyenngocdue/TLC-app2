<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamESGSheetId extends ParentParamReports
{
    protected $referData = 'ESG_tmpl_id';
    protected function getDataSource()
    {
        $sql = "SELECT
                        esgs.id AS id,
                        esgs.name AS name,
                        esgs.description AS description ,
                        esgs.esg_tmpl_id AS ESG_tmpl_id

                        FROM esg_sheets esgs
                        WHERE 1 = 1
                        ORDER BY esgs.name";
        $result = DB::select($sql);
        return $result;
    }
}
