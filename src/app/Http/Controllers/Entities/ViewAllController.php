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
    use TraitViewAllMatrixApproveMultiController;
    use TraitViewAllKanbanController;

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

        if (in_array($table, $calendar_apps)) return 'calendar';
        if (in_array($table, $matrix_apps)) return 'matrix';
        if (in_array($table, $kanban_apps)) return 'kanban';
        return null;
    }

    public function index(Request $request, $trashed = false)
    {
        if (CurrentUser::get()->isExternalInspector()) {
            //return "<x-feedback.result type='warning' title='Permission Denied' message='You are not permitted to view this check sheet.<br/>If you believe this is a mistake, please contact our admin.' />";
            //show User does not have the right permissions #789
            return abort(403);
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
            case 'matrix_print':
                return $this->indexViewAllMatrixPrint($request);
            case "matrix_approve_multi":
                return $this->indexViewAllMatrixApproveMulti($request);
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
