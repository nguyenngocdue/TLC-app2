<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Ghg_tmpl;
use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamGhgTmpl extends ParentParamReports
{
    protected $referData = '';
    protected function getDataSource()
    {
        $list = Ghg_tmpl::where('deleted_at', NULL)->get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => trim(substr($team['name'], 4))];
        return $dataSource;
    }
}
