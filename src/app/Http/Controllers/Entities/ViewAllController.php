<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityAdvancedFilter;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllTable;
use App\Http\Controllers\UpdateUserSettings;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\JsonControls;
use App\Utils\System\Timer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ViewAllController extends Controller
{
    use TraitEntityDynamicType;
    use TraitEntityAdvancedFilter;

    use TraitViewAllTable;

    use TraitViewAllTableController;
    use TraitViewAllCalendarController;
    use TraitViewAllMatrixController;
    use TraitViewAllMatrixPrintController;
    use TraitViewAllMatrixSignatureController;
    use TraitViewAllMatrixApproveMultiController;
    use TraitViewAllKanbanController;
    use TraitViewAllTreeExplorerController;

    protected $type = "";
    protected $typeModel = '';
    protected $permissionMiddleware;

    private $frameworkTook = 0;

    public function __construct()
    {
        $this->frameworkTook = Timer::getTimeElapseFromLastAccess();
        $this->assignDynamicTypeViewAll();
        $this->middleware("permission:{$this->permissionMiddleware['read']}")->only('index');
    }

    public function getType()
    {
        return $this->type;
    }

    private function getDefaultViewType()
    {
        $table = Str::plural($this->type);
        $calendar_apps = JsonControls::getAppsHaveViewAllCalendar();
        $matrix_apps = JsonControls::getAppsHaveViewAllMatrix();
        $kanban_apps = JsonControls::getAppsHaveViewAllKanban();
        $tree_apps = JsonControls::getAppsHaveViewAllTreeExplorer();

        if (in_array($table, $calendar_apps)) return 'calendar';
        if (in_array($table, $matrix_apps)) return 'matrix';
        if (in_array($table, $kanban_apps)) return 'kanban';
        if (in_array($table, $tree_apps)) return 'tree_explorer';
        return null;
    }

    public function index(Request $request, $trashed = false)
    {
        $cu = CurrentUser::get();
        if ($cu->isExternalInspector() || $cu->isShippingAgent() || $cu->isCouncilMember()) return abort(403, "External users are not allowed to access this page.");

        if ($cu->isProjectClient()) {
            if ($cu->isAllowedDocType()) {
                // dump("Do crazy thing here");
            } else {
                return abort(403, "External users are not allowed to access this resource.");
            }
        }

        if ($viewType = $request->input('view_type')) {
            (new UpdateUserSettings())($request);
            switch ($viewType) {
                case 'calendar':
                    return redirect($request->getPathInfo() . '#scroll-to-month');
                    break;
                default:
                    return redirect($request->getPathInfo());
                    break;
            }
        }
        [,,,,,,,, $viewAllModel] = $this->getUserSettingsViewAll();
        $viewAllModel = $viewAllModel ? $viewAllModel : $this->getDefaultViewType();
        switch ($viewAllModel) {
            case 'calendar':
                return $this->indexViewAllCalendar($request);
            case 'matrix':
                return $this->indexViewAllMatrix($request);
                // case 'matrix_print':
                //     return $this->indexViewAllMatrixPrint($request);
            case "matrix_approve_multi":
                return $this->indexViewAllMatrixApproveMulti($request);
            case "tree_explorer":
                return $this->indexViewAllTreeExplorer($request);
            case "matrix_signature":
                return $this->indexViewAllMatrixSignature($request);
            case "kanban":
                return $this->indexViewAllKanban($request);
            case 'table':
            default:
                return $this->indexViewAllTable($request, $trashed);
        }
    }
    public function indexTrashed(Request $request)
    {
        return $this->index($request, true);
    }
}
