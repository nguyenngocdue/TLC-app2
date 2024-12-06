<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\Department;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MyOrgChartController extends Controller
{
    const TSW_ID = 1;

    use TraitViewAllFunctions;
    public function getType()
    {
        return "dashboard";
    }

    private function getUserSettings($orgChartMode)
    {
        $settings = CurrentUser::getSettings();
        $settings['org_chart']['org_chart_mode'] = $orgChartMode;
        return $settings['org_chart'];
    }

    private function getOptionsRenderByUserSetting($showOptions)
    {
        $results = [
            'loadResigned' => [0],
            'loadWorker' => [],
            'loadOnlyBod' => [0, 1],
        ];
        foreach ($showOptions as $key => $value) {
            switch ($key) {
                case 'loadResigned':
                    if ($value == 'true') $results['loadResigned'] = [0, 1];
                    break;
                case 'loadWorker':
                    if ($value == 'true') $results['loadWorker'] = [$this::TSW_ID]; //#1: TSW
                    break;
                case 'loadOnlyBod':
                    if ($value == 'true') $results['loadOnlyBod'] = [1];
                    break;
                default:
                    break;
            }
        }
        return $results;
    }

    public function index(Request $request)
    {
        $orgChartMode = 'standard_mode';
        if ($request->has('mode')) $orgChartMode = $request->mode;

        $viewSettings = $this->getUserSettings($orgChartMode);

        $showOptions = $this->getUserSettingsViewOrgChart();
        $options = $this->getOptionsRenderByUserSetting($showOptions);

        $bodOptions = $this->getOptionsRenderByUserSetting(['is_bod' => 'true']);
        $printOptions = $this->getOptionsRenderByUserSetting([]);

        $departments = Department::query()
            ->where('hide_in_org_chart', 0)
            ->orderBy('order_no')
            ->get();

        return view(
            'utils.my-org-chart',
            [
                'showOptions' => $showOptions,
                'options' => $options,

                'bodOptions' => $bodOptions,
                'printOptions' => $printOptions,

                'departments' => $departments,
                'viewSettings' => $viewSettings,
                'isAdmin' => CurrentUser::isAdmin(),
                'topTitle' => 'Company ORG Chart'
            ]
        );
    }
}
