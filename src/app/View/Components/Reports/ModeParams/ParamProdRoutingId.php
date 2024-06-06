<?php

namespace App\View\Components\Reports\ModeParams;

use App\Utils\Support\ParameterReport;
use App\View\Components\Reports\ParentParamReports;

class ParamProdRoutingId extends ParentParamReports
{
    protected $referData = 'sub_project_id';
    protected $referData1 = 'checksheet_type_id';
    protected function getDataSource()
    {

        $type = $this->getType();
        // dump($type);
        $typeIdOfShowOnScreen = '';
        switch ($type) {
            case 'qaqc_wirs':
                $typeIdOfShowOnScreen = 346; // QAQC WIR
                break;
            case 'prod_sequences':
                $typeIdOfShowOnScreen = 347; // SQB Timesheet
                break;
                /*             case 'qaqc_ncrs':
                    $typeIdOfShowOnScreen = 346; // QAQC Inspection
                break;  */
            default:
                $typeIdOfShowOnScreen = '';
                break;
        }


        $configData = ParameterReport::getConfigByName('prod_routing_id');
        $targetIds = ParameterReport::getTargetIds($configData);
        $prodRoutings = ParameterReport::getDBParameter($targetIds, 'Prod_routing');
        $result = [];
        // dump($prodRoutings->toArray());

        foreach ($prodRoutings as $routing) {
            if ($routing->name === '-- available' || empty($routing->getScreensShowMeOn->toArray())) continue;

            $ids = $routing->getScreensShowMeOn->pluck('id')->toArray();

            $r = '';
            if ($typeIdOfShowOnScreen && in_array((int)$typeIdOfShowOnScreen, $ids)) $r = $routing;
            elseif (!$typeIdOfShowOnScreen)  $r = $routing;

            if ($r) {
                $subProjectIds = $r->getSubProjects->pluck('id')->toArray();
                $chklstTmplIds = $r->getChklstTmpls->pluck('id')->toArray();

                $result[] = (object)[
                    'id' => $r->id,
                    'name' => $r->name,
                    $this->referData => $subProjectIds,
                    $this->referData1 => $chklstTmplIds
                ];
            }
        }

        usort($result, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });
        return $result;
    }
}
