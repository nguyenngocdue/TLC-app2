<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityAdvancedFilter;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllTable;
use App\Http\Controllers\UpdateUserSettings;
use App\Utils\System\Timer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ViewAllController extends Controller
{
    use TraitEntityDynamicType;
    use TraitEntityAdvancedFilter;

    use TraitViewAllTable;

    use TraitViewAllTableController;
    use TraitViewAllCalendarController;
    use TraitViewAllMatrixController;

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
        // dump($this->type);
        switch ($this->type) {
            case "hr_timesheet_worker":
            case "qaqc_wir":
                return 'matrix';
            case "hr_timesheet_officer":
                return 'calendar';
            default:
                return null;
        }
    }

    public function index(Request $request, $trashed = false)
    {
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
