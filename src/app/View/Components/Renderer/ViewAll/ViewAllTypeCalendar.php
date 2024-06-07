<?php

namespace App\View\Components\Renderer\ViewAll;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Pj_task;
use App\Models\Project;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ViewAllTypeCalendar extends Component
{
    use TraitViewAllFunctions;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource = null,
        private $type,
        private $typeModel,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataSource = $this->dataSource;
        $token = CurrentUser::getTokenForApi();
        [, $filterViewAllCalendar] = $this->getUserSettingsViewAllCalendar();
        $ownerId = isset($filterViewAllCalendar['owner_id']) ? $filterViewAllCalendar['owner_id'][0] : CurrentUser::id();
        $userCurrentCalendar = User::findFromCache($ownerId);
        $dataCountWeek = $this->getDataCountOfWeek($ownerId);
        $allTimesheet = $dataSource->get()->map(function ($item) use ($dataCountWeek) {
            $item['count_duplicate'] = $dataCountWeek[$item->week]->count_duplicate;
            $item['week_value'] = $this->getWeekByDay($item->week)[1];
            $item['year_value'] = $this->getWeekByDay($item->week)[3];
            $item['url'] = route($item->getTable() . '.edit', $item->id);
            $item['bg_color'] = $this->getColorForStatus($item->status)[0];
            $item['text_color'] = $this->getColorForStatus($item->status)[1];
            return $item;
        })->groupBy('year_value');
        $listIdPendingApproval = $this->getListIdPendingApproval($dataSource);
        $nodeProjectTreeArray = json_encode(array_values(Project::getSubProjectTree()));
        $nodeTaskTreeArray = json_encode(array_values(Pj_task::getTaskTreeOfUser($ownerId)));
        // dump($nodeProjectTreeArray);
        // dump($nodeTaskTreeArray);

        return view('components.renderer.view-all.view-all-type-calendar', [
            'allTimesheet' => $allTimesheet,
            'routeCreate' => route($this->type . '.storeEmpty'),
            'token' => $token,
            'type' => $this->type,
            'typeModel' => $this->typeModel,
            'year' => $filterViewAllCalendar['year'] ?? '',
            'userCurrentCalendar' => $userCurrentCalendar,
            'titleLegend' => 'Legend',
            'ownerId' => $ownerId,
            'nodeProjectTreeArray' => $nodeProjectTreeArray,
            'nodeTaskTreeArray' => $nodeTaskTreeArray,
            'listIdPendingApproval' => $listIdPendingApproval,
            'routeChangeStatusMultiple' => route("{$this->type}.changeStatusMultiple"),
            'disableButton' => $this->disableButtonApproveAll($listIdPendingApproval, $userCurrentCalendar),
        ]);
    }
    private function disableButtonApproveAll($listIdPendingApproval, $userCurrentCalendar)
    {
        return empty($listIdPendingApproval) || ($userCurrentCalendar->id == CurrentUser::id());
    }
    private function getListIdPendingApproval($dataSource)
    {
        return $dataSource->get()->where('status', 'pending_approval')->pluck('id')->toArray() ?? [];
    }
    private function getDataCountOfWeek($ownerId)
    {
        $dataQuery = DB::select("SELECT week , count(*) as count_duplicate
        FROM $this->type 
        WHERE owner_id = $ownerId
        AND deleted_at is null
        GROUP BY week
        ");
        $result = [];
        foreach ($dataQuery as $value) {
            $result[$value->week] = $value;
        }
        return $result;
    }

    private function getWeekByDay($day)
    {
        $date = Carbon::parse($day);
        return [$date->day, $date->weekOfYear, $date->month, $date->year];
    }
    private function getColorForStatus($status)
    {
        $statuses = LibStatuses::getAll();
        $bgColor = $statuses[$status]['bg_color'];
        $textColor = $statuses[$status]['text_color'];
        return [$bgColor, $textColor];
    }
}
