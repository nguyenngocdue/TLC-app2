<?php

namespace App\View\Components\Renderer\OrgChart;

use App\Models\Department;
use App\Models\User;
use App\Models\User_position;
use App\Models\Workplace;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Tree\BuildTree;
use App\Utils\Support\Tree\BuildTreeOrgChart;
use Illuminate\Support\Facades\App;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class OrgChartRenderer extends Component
{
    const ARRAY_RESIGNED = [0, 1];
    const DPM_BOD = 2;

    function __construct(
        private $id,
        private $departmentId = null,
        private $options = null,
        private $departments = null,
        private $headIds = [],

        private $isApprovalView = null,
        private $isPrintMode = null,
        private $zoomToFit = null,
    ) {
        // dd($departments);
        // dump($zoomToFit);
    }

    private function getTreeByDepartment($departmentId, $options, $isApprovalView)
    {
        $tree = $isApprovalView ? BuildTree::getTree() : BuildTreeOrgChart::getTree();
        $this->setTreeByDepartment($tree, $departmentId, $isApprovalView);
        $results = [];
        if ($this->departments) {
            $this->headIds = $this->departments->pluck('head_of_department')->toArray();
        }
        // dump($heads);
        $this->restructureTree($tree, $results, $options, true);
        usort($results, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        return $results;
    }

    private function restructureTree($tree, &$results, $options, $isRoot = false)
    {
        foreach ($tree as $value) {
            if (isset($value->children)) {
                $isCurrentNodeAHOD = in_array($value->id, $this->headIds);
                if ($isRoot) {
                    $this->restructureTree($value->children, $results, $options);
                } elseif (!$isCurrentNodeAHOD) {
                    $this->restructureTree($value->children, $results, $options);
                }
                // $this->restructureTree($value->children, $results, $options);
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

    private function setTreeByDepartment(&$tree, $departmentId, $isApprovalView)
    {
        if ($departmentId) {
            $departmentId = $departmentId == "null" ? $this::DPM_BOD : $departmentId;
            $department = Department::findFromCache($departmentId);
            $headOfDepartmentId = $department->head_of_department ?? null;
            if ($headOfDepartmentId) {
                $tree = $isApprovalView ? BuildTree::getTreeById($headOfDepartmentId) : BuildTreeOrgChart::getTreeById($headOfDepartmentId);
            }
        }
    }

    private function convertDataSource($value, $options)
    {
        if (
            in_array($value->workplace, $options['workplace'])
            && in_array($value->resigned, $options['resigned'])
            && in_array($value->time_keeping_type, $options['time_keeping_type'])
            && in_array($value->is_bod, $options['is_bod'])
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

    private function getFillColor($item)
    {
        $color = CurrentUser::isAdmin() ?  '#fed7aa' : '#ffffff';
        return $item->resigned == 1 ? '#d1d5db' : ($item->time_keeping_type == 3 ? $color : "#ffffff");
    }

    public function render()
    {
        // dd($this->departmentId);
        $tree = $this->getTreeByDepartment($this->departmentId, $this->options, $this->isApprovalView);
        // dump($this->zoomToFit);
        return view("components.renderer.org-chart.org-chart-renderer", [
            'id' => $this->id,
            'dataSource' => $tree,
            'department' => Department::findFromCache($this->departmentId),
            'zoomToFit' => $this->zoomToFit,
        ]);
    }
}
