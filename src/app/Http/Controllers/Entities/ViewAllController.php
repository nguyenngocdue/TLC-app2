<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityAdvancedFilter;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllTable;
use App\Http\Controllers\UpdateUserSettings;
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

    private function getTabs()
    {
        $tabs = [];
        $tableName = Str::plural($this->type);
        if (in_array($tableName, JsonControls::getAppsHaveViewAllCalendar())) {
            $tabs = [
                'home' => [
                    'href' => "?view_type=table&action=updateViewAllMode&_entity=$tableName",
                    'title' => "View All Table",
                    'icon' => 'fa-solid fa-house',
                    'active' => true,
                ],
                'calendar' => [
                    'href' => "?view_type=calendar&action=updateViewAllMode&_entity=$tableName",
                    'title' => "View All Calendar",
                    'icon' => 'fa-regular fa-calendar',
                    'active' => false,
                ]
            ];
        };
        return $tabs;
    }

    public function index(Request $request, $trashed = false)
    {
        if ($request->input('view_type')) {
            (new UpdateUserSettings())($request);
            return redirect($request->getPathInfo());
        }
        [,,,,,,,, $viewAllModel] = $this->getUserSettings();
        switch ($viewAllModel) {
            case 'calendar':
                return $this->indexViewAllCalendar($request);
                break;
            case 'table':
            default:
                return $this->indexViewAllTable($request, $trashed);
                break;
        }
    }
    public function indexTrashed(Request $request)
    {
        return $this->index($request, true);
    }
}
