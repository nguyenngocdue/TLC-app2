<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\Department;
use App\Models\User;
use App\Models\User_category;
use App\Models\User_position;
use App\Models\User_time_keep_type;
use App\Models\Workplace;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Tree\BuildTree;
use App\Utils\Support\Tree\BuildTreeOrgChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MyOrgChartController extends Controller
{
    const ARRAY_TSO_NONE = [2, 3];

    const ARRAY_RESIGNED = [0, 1];

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
        $approvalView = $request->has('approval-tree');
        $settingsView = $this->userSettings($approvalView);
        $tree = $approvalView ? BuildTree::getTree() : BuildTreeOrgChart::getTree();
        $results = [];
        $showOptions = $this->getUserSettingsViewOrgChart();
        $this->transformDataTree($tree, $showOptions, $approvalView);
        $this->x($tree, $results, $this->getOptionsRenderByUserSetting($showOptions));
        usort($results, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        return view(
            'utils.my-org-chart',
            [
                'dataSource' => $results,
                'showOptions' => $showOptions,
                'settingsView' => $settingsView,
                'isAdmin' => CurrentUser::isAdmin(),
                'topTitle' => 'Company ORG Chart'
            ]
        );
    }
    private function userSettings($approvalView)
    {
        $settings = CurrentUser::getSettings();
        $settings['org_chart']['approval_view'] = $approvalView;
        return $settings['org_chart']['approval_view'] ?? false;
    }
    private function transformDataTree(&$tree, $showOptions = [], $approvalView)
    {
        if (isset($showOptions['department'])) {
            $department = Department::findFromCache($showOptions['department']);
            $headOfDepartmentId = $department->head_of_department;
            if ($headOfDepartmentId) {
                $tree = $approvalView ? BuildTree::getTreeById($headOfDepartmentId) : BuildTreeOrgChart::getTreeById($headOfDepartmentId);
            }
        }
    }
    private function x($tree, &$results, $options)
    {
        foreach ($tree as $value) {
            if (isset($value->children)) {
                $this->x($value->children, $results, $options);
            }
            if (App::isProduction()) {
                if ($value->show_on_beta == 0) {
                    $a = $this->convertDataSource($value, $options);
                    if ($a) $results[] = $a;
                }
            } else {
                $a = $this->convertDataSource($value, $options);
                if ($a) $results[] = $a;
            }
        }
    }
    private function getMemberCount($value, $options)
    {
        if (isset($value->children)) {
            if ($options == $this::ARRAY_RESIGNED) return sizeof($value->children);
            else {
                $count = 0;
                foreach ($value->children as $user) {
                    if ($user->resigned == 0) $count++;
                }
                return $count;
            }
        }
        return '';
    }
    private function convertDataSource($value, $options)
    {
        if (
            in_array($value->workplace, $options['workplace']) && in_array($value->resigned, $options['resigned'])
            && in_array($value->time_keeping_type, $options['time_keeping_type']) && in_array($value->is_bod, $options['is_bod'])
        ) {
            $id = $value->id;
            $user = User::findFromCache($id);
            $positionRendered = isset($user->position) ? User_position::findFromCache($user->position)->name : "";
            // $positionRendered = $user->position_rendered;
            // $positionRendered = $user->getPosition->name;
            $workplace = isset($value->workplace) ? Workplace::findFromCache($value->workplace)->name : '';

            $email = $user->email;
            $avatar = $user->getAvatarThumbnailUrl() ?? '';
            $memberCount = $this->getMemberCount($value, $options['resigned']);
            $memberCount = $memberCount ? '(' . sprintf("%02d", $memberCount) . ')' : '';
            return [
                'key' => $id,
                'name' => $value->name0,
                'employeeidAndWorkplace' => $user->employeeid . ($workplace ? (' - ' . $workplace) : ''),
                'parent' => $value->parent_id,
                'phone' => $user->phone,
                'avatar' => $avatar,
                'email' => $email,
                'fill' => $this->getFillColor($value),
                'title' => $positionRendered,
                'url' => "/profile/$id",
                'memberCount' => $memberCount,
            ];
        }
    }
    private function getFillColor($item)
    {
        $color = CurrentUser::isAdmin() ?  '#fed7aa' : '#ffffff';
        return $item->resigned == 1 ? '#d1d5db' : ($item->time_keeping_type == 3 ? $color : "#ffffff");
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
