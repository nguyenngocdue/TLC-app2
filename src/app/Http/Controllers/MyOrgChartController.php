<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\Department;
use App\Models\User_category;
use App\Models\User_time_keep_type;
use App\Models\Workplace;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MyOrgChartController extends Controller
{
    const ARRAY_RESIGNED = [0, 1];
    const ARRAY_TSO_NONE = [2, 3];
    const ARRAY_NONE_RESIGNED = [0];
    const IS_BOD = [1];
    const ARRAY_BOD = [0, 1];

    use TraitViewAllFunctions;
    public function getType()
    {
        return "dashboard";
    }
    public function index(Request $request)
    {
        $isApprovalView = $request->has('approval-tree');
        $isAdvancedMode = $request->has('advanced-mode');
        $viewSettings = $this->getUserSettings($isApprovalView, $isAdvancedMode);

        $showOptions = $this->getUserSettingsViewOrgChart();
        $options = $this->getOptionsRenderByUserSetting($showOptions);

        $bodOptions = $this->getOptionsRenderByUserSetting(['is_bod' => 'true']);
        $printOptions = $this->getOptionsRenderByUserSetting([]);

        $departments = Department::query()
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

    private function getUserSettings($isApprovalView, $isAdvancedMode)
    {
        $settings = CurrentUser::getSettings();
        $settings['org_chart']['approval_view'] = $isApprovalView;
        $settings['org_chart']['advanced_mode'] = $isAdvancedMode;
        return $settings['org_chart'];
    }

    private function getIdsWorkplace()
    {
        return Workplace::query()->get()->pluck('id')->toArray();
    }
    private function getIdsUserTimeKeepingType()
    {
        return User_time_keep_type::query()->get()->pluck('id')->toArray();
    }
    private function getIdsUserCategories()
    {
        return User_category::query()->get()->pluck('id')->toArray();
    }
    private function getOptionsRenderByUserSetting($showOptions)
    {
        $results = [
            'resigned' => $this::ARRAY_NONE_RESIGNED,
            'time_keeping_type' => $this::ARRAY_TSO_NONE,
            'workplace' => $this->getIdsWorkplace(),
            'is_bod' => $this::ARRAY_BOD,
        ];
        foreach ($showOptions as $key => $value) {
            switch ($key) {
                case 'resigned':
                    if ($value == 'true') $results['resigned'] = $this::ARRAY_RESIGNED;
                    break;
                case 'time_keeping_type':
                    if ($value == 'true') $results['time_keeping_type'] = $this->getIdsUserTimeKeepingType();
                    break;
                case 'workplace':
                    if (is_array($value)) $results['workplace'] = $value;
                    break;
                case 'is_bod':
                    if ($value == 'true') $results['is_bod'] = $this::IS_BOD;
                    break;
                default:
                    break;
            }
        }
        return $results;
    }
}
