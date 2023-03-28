<?php

namespace App\View\Components\Navigation;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Breadcrumb extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        $type = CurrentRoute::getTypePlural();
        $singular = CurrentRoute::getTypeSingular();
        $blackList = ['dashboard', 'permission', 'manageApp', 'manageStatus', 'manageWidget', 'reportIndex'];
        if (in_array($singular, $blackList)) return "";
        $links = [];
        $isAdmin = CurrentUser::isAdmin();
        $action = CurrentRoute::getControllerAction();
        $id = CurrentRoute::getEntityId($singular);
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
                $links[] = ['href' => null, 'modePrint' => true, 'title' => 'Print Now', 'icon' => '<i class="fa-duotone fa-print"></i>'];
                $links[] = ['href' => route($type . '.edit', $id), 'title' => 'Edit Mode', 'icon' => '<i class="fa-duotone fa-pen-to-square"></i>'];
                break;
            case 'showQRApp':
                $slug = CurrentRoute::getEntitySlug($singular);
                $modelPath = Str::modelPathFrom($singular);
                $id = $modelPath::where('slug', $slug)->first()['id'];
                // $links[] = ['href' => null, 'title' => 'Export PDF', 'icon' => '<i class="fa-solid fa-file-export"></i>', 'id' => 'export-pdf'];
                $links[] = ['href' => route($type . '.edit', $id), 'title' => 'Edit Mode', 'icon' => '<i class="fa-duotone fa-pen-to-square"></i>'];
                break;
            case 'showQRCode':
                // $links[] = ['href' => null, 'title' => 'Export PDF', 'icon' => '<i class="fa-solid fa-file-export"></i>', 'id' => 'export-pdf'];
                break;
            case 'edit':
                $links[] = ['href' => route($type . '.show', $id), 'title' => 'Print Mode', 'icon' => '<i class="fa-duotone fa-print"></i>'];
                break;
            default:
                break;
        }
        $links[] = ['href' => route($type . '.index'), 'title' => 'View All', 'icon' => '<i class="fa-solid fa-table-cells"></i>'];
        $links[] = ['href' => route($type . '.create'), 'title' => 'Add New', 'icon' => '<i class="fa-regular fa-file-plus"></i>'];
        if ($isAdmin) {
            $links[] = ['href' => route($singular . '_prp.index'), 'title' => 'Workflows', 'icon' => '<i class="fa-duotone fa-sitemap"></i>'];
        }
        return view('components.navigation.breadcrumb', [
            'links' => $links,
            'type' => $type,
        ]);
    }
}
