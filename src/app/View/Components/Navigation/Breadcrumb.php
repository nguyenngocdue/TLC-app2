<?php

namespace App\View\Components\Navigation;

use App\Http\Controllers\Reports\ReportIndexController;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\View\Components\Controls\DisallowedDirectCreationChecker;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Breadcrumb extends Component
{
    public function __construct()
    {
    }

    private function makeUpReports($allReports)
    {
        if (empty($allReports)) return [];
        // dump($allReports);
        $result = [];
        foreach ($allReports as $reportType => $reports) {
            foreach ($reports as $mode => $report) {
                $result[$reportType][] = [
                    'title' => $report['title'],
                    'href' => route($report['path']),
                    'mode' => $reportType . " " . $mode,
                ];
            }
        }
        return $result;
    }
    public function render()
    {
        $type = CurrentRoute::getTypePlural();
        $singular = CurrentRoute::getTypeSingular();
        $blackList = [
            'dashboard',
            'admin_permission',
            'manageApp',
            'manageAppCreation',
            'manageStandardProp',
            'manageStandardDefaultValue',
            'manageStatus',
            'manageWidget',
            'reportIndex',
        ];
        $permissionList = [
            'permission',
            'role',
            'role_set'
        ];
        if (in_array($singular, $blackList)) return "";
        $links = [];
        $isAdmin = CurrentUser::isAdmin();
        if (in_array($singular, $permissionList)) {
            switch ($singular) {
                case 'permission':
                    $links[] = ['href' => route('setroles.index'), 'title' => 'Set Roles', 'icon' => '<i class="fa-duotone fa-backward-fast"></i>'];
                    $links[] = ['href' => route('setrolesets.index'), 'title' => 'Set Role Sets', 'icon' => '<i class="fa-duotone fa-backward-fast"></i>'];
                    break;
                case 'role':
                    $links[] = ['href' => route('setpermissions.index'), 'title' => 'Set Permission', 'icon' => '<i class="fa-duotone fa-backward-fast"></i>'];
                    $links[] = ['href' => route('setrolesets.index'), 'title' => 'Set Role Sets', 'icon' => '<i class="fa-duotone fa-backward-fast"></i>'];
                    break;
                case 'role_set':
                    $links[] = ['href' => route('setpermissions.index'), 'title' => 'Set Permission', 'icon' => '<i class="fa-duotone fa-backward-fast"></i>'];
                    $links[] = ['href' => route('setroles.index'), 'title' => 'Set Role Sets', 'icon' => '<i class="fa-duotone fa-backward-fast"></i>'];
                    break;
                default:
                    break;
            }
            return view('components.navigation.breadcrumb', [
                'links' => $links,
                'type' => $type,
                'classList' => 'px-2 py-1 text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 mr-1 my-2',
            ]);
        }
        $action = CurrentRoute::getControllerAction();
        $id = CurrentRoute::getEntityId($singular);
        $allReports = ReportIndexController::getReportOf($type);
        $allReports = $this->makeUpReports($allReports);
        $disallowedDirectCreationChecker = DisallowedDirectCreationChecker::check($type);
        if ($isAdmin) {
            $modelPath = Str::modelPathFrom($singular);
            $first = $modelPath::first();
            $first_id = $first ? $first->id : null;
            if ($first_id) {
                $links[] = ['href' => route($type . '.edit', $first_id), 'title' => 'View First', 'icon' => '<i class="fa-duotone fa-backward-fast"></i>'];
            }
            if (in_array($singular, ['attachment', 'comment'])) {
                $links[] = ['href' => route($singular . '_ppt.index'), 'title' => 'Properties', 'icon' => '<i class="fa-solid fa-square-sliders-vertical"></i>'];
            }
        }
        switch ($action) {
            case 'show':
                // $links[] = ['href' => null, 'title' => 'Export PDF', 'icon' => '<i class="fa-solid fa-file-export"></i>', 'id' => 'export-pdf'];
                $links[] = ['href' => null, 'type' => 'modePrint', 'title' => 'Print Now', 'icon' => '<i class="fa-duotone fa-print"></i>'];
                $links[] = ['href' => route($type . '.edit', $id), 'title' => 'Edit Mode', 'icon' => '<i class="fa-duotone fa-pen-to-square"></i>'];
                break;
            case 'showQRApp':
                $slug = CurrentRoute::getEntitySlug($singular);
                $modelPath = Str::modelPathFrom($singular);
                $id = $modelPath::where('slug', $slug)->first()['id'];
                // $links[] = ['href' => null, 'title' => 'Export PDF', 'icon' => '<i class="fa-solid fa-file-export"></i>', 'id' => 'export-pdf'];
                $links[] = ['href' => route($type . '.edit', $id), 'title' => 'Edit Mode', 'icon' => '<i class="fa-duotone fa-pen-to-square"></i>'];
                break;
            case 'edit':
                $links[] = ['href' => route($type . '.show', $id), 'title' => 'View Mode', 'icon' => '<i class="fa-duotone fa-browser"></i>'];
                break;
            default:
                break;
        }
        $links[] = ['href' => route($type . '.index'), 'title' => 'View All', 'icon' => '<i class="fa-solid fa-table-cells"></i>'];
        $links[] = ['href' => route($type . '.trashed'), 'title' => 'View Trash', 'icon' => '<i class="fa-solid fa-trash"></i>'];
        if (!$disallowedDirectCreationChecker) $links[] = ['href' => route($type . '.create'), 'title' => 'Add New', 'icon' => '<i class="fa-regular fa-file-plus"></i>'];
        if (!empty($allReports)) $links[] = ['type' => 'report', 'title' => 'View Report', 'dataSource' => $allReports, 'icon' => '<i class="fa-regular fa-file-chart-column"></i>'];
        if ($isAdmin) {
            $links[] = ['href' => route($singular . '_prp.index'), 'title' => 'Workflows', 'icon' => '<i class="fa-duotone fa-sitemap"></i>'];
        }
        return view('components.navigation.breadcrumb', [
            'links' => $links,
            'type' => $type,
            'classList' => 'px-2 py-1 text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 mr-1 my-2',
        ]);
    }
}
