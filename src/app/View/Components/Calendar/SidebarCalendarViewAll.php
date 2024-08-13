<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\User;
use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class SidebarCalendarViewAll extends Component
{
    use TraitSupportPermissionGate;
    use TraitViewAllFunctions;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    const TIME_KEEPING_TYPE_TSO = 2;
    const TIME_KEEPING_TYPE_NONE = 3;

    const PROJECT_CLIENT_ID = 128;

    public function __construct(
        private $type,
        private $typeModel,
    ) {
        //
    }

    function removeUserNoNeedSTS($tree)
    {
        $filteredTree = [];

        foreach ($tree as $key => $node) {
            // Recursively filter children
            if (isset($node->children) && !empty($node->children)) {
                $node->children = $this->removeUserNoNeedSTS($node->children);
            }

            // Check if the node should be kept
            if ($node->resigned != 1) {
                if ($node->time_keeping_type == 2 || !empty($node->children)) {
                    $filteredTree[$key] = $node;
                }
            }
        }
        // dump($filteredTree);
        return $filteredTree;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $type = $this->type;
        $user = CurrentUser::get();
        $userId = $user->id;
        [, $filterViewAllCalendar, $viewAllCalendarShowAllChildren] = $this->getUserSettingsViewAllCalendar();
        $idsRenderCalendar = $filterViewAllCalendar['owner_id'] ?? [$userId];
        $tree = $this->getTreeOwnerIds($user);
        $tree = $this->removeUserNoNeedSTS($tree);
        $ids = $this->getListOwnerIds($user);
        $idsFormatQuery = join(',', $ids);
        $dataCountQuerySql = $this->queryTotalPendingApprovalBySql($idsFormatQuery);
        $dataUserCurrent = $this->setDataForUser($user, $type, $idsRenderCalendar, $this->typeModel, $dataCountQuerySql);
        $users = $this->queryUsersSql($ids);
        $users = $this->transformUserData($users);
        $dataSource = $this->treeDataSource($tree, $type, $idsRenderCalendar, $dataCountQuerySql);

        $htmlRenderTree = $this->renderHtmlByTreeDataSource($dataSource, $users, $viewAllCalendarShowAllChildren);
        return view('components.calendar.sidebar-calendar-view-all', [
            'htmlRenderCurrentUser' => $this->renderHtml($dataUserCurrent, $users, true),
            'htmlRenderTree' => $htmlRenderTree,
            'url' => route('updateUserSettingsApi'),
            'type' => $type,
            'isChecked' => $viewAllCalendarShowAllChildren == "true",
        ]);
    }
    private function queryUsersSql($ids)
    {
        $ids = join(',', $ids);
        $timeKeepingTypeTSO = $this::TIME_KEEPING_TYPE_TSO;
        $timeKeepingTypeNONE = $this::TIME_KEEPING_TYPE_NONE;
        $id = CurrentUser::id();
        return DB::select(
            "SELECT * FROM users 
            WHERE ((id IN ($ids) 
            AND time_keeping_type IN ($timeKeepingTypeTSO,$timeKeepingTypeNONE)
            AND show_on_beta = 0
            AND resigned = 0)
            OR (id = $id))
            AND deleted_at IS NULL
            "
        );
    }
    private function queryTotalPendingApprovalBySql($idsFormatQuery)
    {
        $dataCountQuerySql = DB::select("SELECT owner_id, count(*) as total 
        FROM $this->type 
        WHERE 
        status ='pending_approval'
        AND owner_id IN  ($idsFormatQuery)
        AND deleted_at IS NULL
        GROUP BY owner_id
        ");
        $data = [];
        foreach ($dataCountQuerySql as $value) {
            $data[$value->owner_id] = $value;
        }
        return $data;
    }
    private function transformUserData($users)
    {
        $arr = [];
        foreach ($users as $value) {
            $arr[$value->id] = $value;
        }
        return $arr;
    }
    private function treeDataSource($tree, $type, $idsRenderCalendar, $dataCountQuerySql)
    {
        $result = [];
        foreach ($tree as $value) {
            $id = $value->id;
            if ($value->show_on_beta == 0 && $value->resigned == 0 && in_array($value->time_keeping_type, [$this::TIME_KEEPING_TYPE_TSO, $this::TIME_KEEPING_TYPE_NONE])) {
                $result[$id] = $this->setDataForUser($value, $type, $idsRenderCalendar, $this->typeModel, $dataCountQuerySql);
                if (isset($value->children)) {
                    $data = $this->treeDataSource($value->children, $type, $idsRenderCalendar, $dataCountQuerySql);
                    if ($data) {
                        $result[$id]['children'] = $data;
                    }
                } else {
                    if ($value->show_on_beta == 0 && $value->resigned == 0 && in_array($value->time_keeping_type, [$this::TIME_KEEPING_TYPE_TSO, $this::TIME_KEEPING_TYPE_NONE])) {
                        $result[$id] = $this->setDataForUser($value, $type, $idsRenderCalendar, $this->typeModel, $dataCountQuerySql);
                    }
                }
            }
        }
        return $result;
    }
    private function setDataForUser($user, $type, $idsRenderCalendar, $typeModel, $dataCountQuerySql)
    {
        $userId = $user->id;
        if ($typeModel) {
            $totalPendingApproval = isset($dataCountQuerySql[$userId]) ? $dataCountQuerySql[$userId]->total : 0;
        } else {
            $totalPendingApproval = isset($dataCountQuerySql[$userId]) ? $dataCountQuerySql[$userId]->total : 0;
        }
        return [
            'id' => $userId,
            'href' => "?view_type=calendar&action=updateViewAllCalendar&_entity=$type&owner_id%5B%5D=$userId",
            'total_pending_approval' => $totalPendingApproval,
            'disable' => $this->isTimeKeepingTypeTSO($user),
            'active' => in_array($userId, $idsRenderCalendar) ? true : false,

        ];
    }
    private function isTimeKeepingTypeTSO($user)
    {
        return ($user->time_keeping_type == $this::TIME_KEEPING_TYPE_TSO) ? false : true;
    }
    private function renderHtmlByTreeDataSource($treeData, $users, $viewAllCalendarShowAllChildren)
    {
        $classShowAllChildren = $viewAllCalendarShowAllChildren == "true" ? "" : "style='display: none;'";
        $html = '';
        foreach ($treeData as $value) {
            if (isset($value['children'])) {
                $htmlRender = $this->renderHtmlByTreeDataSource($value['children'], $users, $viewAllCalendarShowAllChildren);
                if ($htmlRender) {
                    $html .= $this->renderHtml($value, $users);
                    $html .= "<div class='ml-10 none_only_direct_children' $classShowAllChildren>$htmlRender</div>";
                }
            } else {
                $html .= $this->renderHtml($value, $users);
            }
        }
        return $html;
    }
    private function renderHtml($value, $users, $isCurrentUser = false)
    {
        $html = '';
        $user = $users[$value['id']] ?? '';
        $href = $value['href'];
        $disable = $value['disable'];
        $active = $value['active'];
        $totalPendingApproval = $value['total_pending_approval'];
        // $userStr = json_encode($user);
        $userAvatarRender = Blade::render("<x-renderer.avatar-user uid='$user->id'></x-renderer.avatar-user>");
        $badge = '';
        if ($totalPendingApproval > 0) {
            $badge = Blade::render("<x-renderer.badge>$totalPendingApproval</x-renderer.badge>");
        }
        if ($isCurrentUser) {
            $htmlHref = "<a href='$href' class='flex'>$userAvatarRender" . "$badge</a>";
            $bgAndHover = $disable ? "bg-gray-300" : "bg-gray-200 hover:bg-gray-300 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800";
        } else {
            $htmlHref = $disable ? "<div class='flex'>$userAvatarRender" . "$badge</div>" : "<a href='$href' class='flex'>$userAvatarRender" . "$badge</a>";
            $bgAndHover = $disable ? "bg-gray-400" : "bg-gray-200 hover:bg-gray-300 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800";
        }
        $activeClass = $active ? "border-blue-600" : "";

        //128: Project Client
        if (!in_array($user->discipline, [$this::PROJECT_CLIENT_ID])) {
            $html .= "<li class='123 relative $bgAndHover $activeClass border my-1 p-1 focus:ring-4 font-medium rounded-lg text-sm'>
                    $htmlHref
                  </li>";
        }
        return $html;
    }
}
