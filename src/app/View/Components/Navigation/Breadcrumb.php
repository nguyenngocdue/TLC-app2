<?php

namespace App\View\Components\Navigation;

use App\Http\Controllers\Reports\ReportIndexController;
use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\View\Components\Controls\DisallowedDirectCreationChecker;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Breadcrumb extends Component
{
    private $links = [];
    private $blackList = [
        'dashboard',
        'admin_permission',
        'manageApp',
        'manageApi',
        'managePivotTable',
        'manageReport',
        'manageAppCreation',
        'manageProfileField',
        'manageStandardProp',
        'manageStandardDefaultValue',
        'manageStatus',
        'manageWidget',
        'reportIndex',
    ];
    private $action = null;
    public function __construct()
    {
        $this->action = CurrentRoute::getControllerAction();
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
    private function getTitleAndIconForPrintButton($show_renderer)
    {
        switch ($show_renderer) {
            case "props-renderer":
                $title = 'Print Mode';
                $icon = "fa-duotone fa-print";
                break;
            case "project-renderer":
                $title = 'Project Mode';
                $icon = "fa-duotone fa-city";
                break;
            case "checklist-renderer":
            case "checklist-sheet-renderer":
                $title = 'Print Mode';
                $icon = "fa-duotone fa-check-to-slot";
                break;
            case "qr-app-renderer":
                $title = 'Application Mode';
                $icon = "fa-duotone fa-browser";
                break;
            default:
                $title = "Show Mode";
                $icon = "fa-duotone fa-question";
                break;
        }
        return [$title, $icon];
    }
    private function getBreadcrumbOfPermission($singular, $type)
    {
        switch ($singular) {
            case 'permission':
                $this->links[] = ['href' => route('setroles.index'), 'title' => 'Set Roles', 'icon' => '<i class="fa-duotone fa-backward-fast"></i>'];
                $this->links[] = ['href' => route('setrolesets.index'), 'title' => 'Set Role Sets', 'icon' => '<i class="fa-duotone fa-backward-fast"></i>'];
                break;
            case 'role':
                $this->links[] = ['href' => route('setpermissions.index'), 'title' => 'Set Permission', 'icon' => '<i class="fa-duotone fa-backward-fast"></i>'];
                $this->links[] = ['href' => route('setrolesets.index'), 'title' => 'Set Role Sets', 'icon' => '<i class="fa-duotone fa-backward-fast"></i>'];
                break;
            case 'role_set':
                $this->links[] = ['href' => route('setpermissions.index'), 'title' => 'Set Permission', 'icon' => '<i class="fa-duotone fa-backward-fast"></i>'];
                $this->links[] = ['href' => route('setroles.index'), 'title' => 'Set Role Sets', 'icon' => '<i class="fa-duotone fa-backward-fast"></i>'];
                break;
            default:
                break;
        }
        return view('components.navigation.breadcrumb', [
            'links' => $this->links,
            'type' => $type,
            'classList' => 'px-2 py-1 text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 mr-1 my-2',
        ]);
    }
    private function showButtonViewFirst($singular, $type)
    {
        $modelPath = Str::modelPathFrom($singular);
        $first = $modelPath::first();
        $first_id = $first ? $first->id : null;
        if ($first_id) {
            $this->links[] = ['href' => route($type . '.edit', $first_id), 'title' => 'View First', 'icon' => '<i class="fa-duotone fa-backward-fast"></i>'];
        }
    }
    private function showButtonProperties($singular)
    {
        if (in_array($singular, ['attachment', 'comment'])) {
            $this->links[] = ['href' => route($singular . '_ppt.index'), 'title' => 'Properties', 'icon' => '<i class="fa-solid fa-square-sliders-vertical"></i>'];
        }
    }
    private function showButtonPrintOrEditOrQRApp($singular, $type)
    {
        $id = CurrentRoute::getEntityId($singular);
        switch ($this->action) {
            case 'show':
                // $this->links[] = ['href' => null, 'title' => 'Export PDF', 'icon' => '<i class="fa-solid fa-file-export"></i>', 'id' => 'export-pdf'];
                $this->links[] = ['href' => null, 'type' => 'modePrint', 'title' => 'Print Now', 'icon' => '<i class="fa-duotone fa-print"></i>'];
                $this->links[] = ['href' => route($type . '.edit', $id), 'title' => 'Edit Mode', 'icon' => '<i class="fa-duotone fa-pen-to-square"></i>'];
                break;
            case 'showQRApp':
                $slug = CurrentRoute::getEntitySlug($singular);
                $modelPath = Str::modelPathFrom($singular);
                $id = $modelPath::where('slug', $slug)->first()['id'];
                // $this->links[] = ['href' => null, 'title' => 'Export PDF', 'icon' => '<i class="fa-solid fa-file-export"></i>', 'id' => 'export-pdf'];
                $this->links[] = ['href' => route($type . '.edit', $id), 'title' => 'Edit Mode', 'icon' => '<i class="fa-duotone fa-pen-to-square"></i>'];
                break;
            case 'edit':
                $app = LibApps::getFor($type);
                [$title, $icon] = $this->getTitleAndIconForPrintButton($app['show_renderer']);
                $this->links[] = ['href' => route($type . '.show', $id), 'title' => $title, 'icon' => "<i class='$icon'></i>"];
                break;
            default:
                break;
        }
    }
    private function showButtonViewAll($type)
    {
        $this->links[] = ['href' => route($type . '.index'), 'title' => 'View All', 'icon' => '<i class="fa-solid fa-table-cells"></i>'];
    }
    private function showButtonAddNew($type)
    {
        $disallowedDirectCreationChecker = DisallowedDirectCreationChecker::check($type);
        if (!$disallowedDirectCreationChecker) {
            $this->links[] = ['href' => route($type . '.create'), 'title' => 'Add New', 'icon' => '<i class="fa-regular fa-file-plus"></i>'];
        }
    }
    private function showButtonAddNewByCloning($type)
    {
        if ($type == 'hse_insp_chklst_shts') {
            $modalId = 'clone_hse_insp_chklst_shts';
            $this->links[] = [
                'title' => 'Add New', 'icon' => "<i class='fa-light fa-clone'></i>", 'type' => 'modal',
                'modalId' => $modalId,
                "modalBody" => Blade::render("<x-modals.modal-clone modalId='$modalId'/>")
            ];
        }
    }
    private function showButtonViewReport($type)
    {
        $allReports = ReportIndexController::getReportOf($type);
        $allReports = $this->makeUpReports($allReports);
        if (!empty($allReports)) {
            $this->links[] = ['type' => 'selectDropdown', 'title' => 'View Report', 'dataSource' => $allReports, 'icon' => '<i class="fa-regular fa-file-chart-column"></i>'];
        }
    }
    private function showButtonViewTrash($type)
    {
        if ($this->action == 'index') {
            $this->links[] = ['href' => route($type . '.trashed'), 'title' => 'View Trash', 'icon' => '<i class="fa-solid fa-trash"></i>'];
        }
    }
    private function showButtonWorkflow($singular)
    {
        $this->links[] = ['href' => route($singular . '_prp.index'), 'title' => 'Workflows', 'icon' => '<i class="fa-duotone fa-sitemap"></i>'];
    }
    public function render()
    {
        $singular = CurrentRoute::getTypeSingular();
        $type = CurrentRoute::getTypePlural();
        $isAdmin = CurrentUser::isAdmin();

        if (in_array($singular, $this->blackList)) return "";
        if (in_array($singular, ['permission', 'role', 'role_set']))  return $this->getBreadcrumbOfPermission($singular, $type);

        if ($isAdmin) $this->showButtonViewFirst($singular, $type);
        if ($isAdmin) $this->showButtonProperties($singular);
        $this->showButtonPrintOrEditOrQRApp($singular, $type);
        $this->showButtonViewAll($type);
        $this->showButtonAddNew($type);
        $this->showButtonAddNewByCloning($type);
        $this->showButtonViewReport($type);
        if ($isAdmin) $this->showButtonViewTrash($type);
        if ($isAdmin) $this->showButtonWorkflow($singular);

        $buttonClassList = 'px-2 py-1 text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 mr-1 my-2';
        return view('components.navigation.breadcrumb', [
            'links' => $this->links,
            'type' => $type,
            'classList' => $buttonClassList,
            'buttonClone' => $buttonClone ?? '',
            'modalClone' => $modalClone ?? '',
        ]);
    }
}
