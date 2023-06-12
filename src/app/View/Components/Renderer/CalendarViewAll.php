<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Hr_timesheet_officer;
use App\Utils\Support\CurrentUser;
use Carbon\Carbon;
use Illuminate\View\Component;

class CalendarViewAll extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $token = CurrentUser::getTokenForApi();
        $allTimesheet = Hr_timesheet_officer::query()->where('owner_id', CurrentUser::id())->get()->map(function ($item) {
            // $item['day_value'] = $this->getWeekByDay($item->week)[0];
            $item['week_value'] = $this->getWeekByDay($item->week)[1];
            // $item['month_value'] = $this->getWeekByDay($item->week)[2];
            $item['year_value'] = $this->getWeekByDay($item->week)[3];
            $item['url'] = route($item->getTable() . '.edit', $item->id);
            $item['bg_color'] = $this->getColorForStatus($item->status)[0];
            $item['text_color'] = $this->getColorForStatus($item->status)[1];
            return $item;
        })->groupBy('year_value');
        return view('components.renderer.calendar-view-all', [
            'allTimesheet' => $allTimesheet,
            'routeCreate' => route('timesheet_officers.create'),
            'token' => $token,
        ]);
    }

    private function getWeekByDay($day)
    {
        $date = Carbon::parse($day);
        return [$date->day, $date->weekOfYear, $date->month, $date->year];
    }
    private function getColorForStatus($status)
    {
        $statuses = LibStatuses::getAll();
        $color = isset($statuses[$status]) ? $statuses[$status]['color'] : 'red';
        $colorIndex = isset($statuses[$status]) ? $statuses[$status]['color_index'] : 200;
        $bgIndex = 1000 - $colorIndex;
        $bgColor = $color . '-' . $colorIndex;
        $textColor = $color . '-' . $bgIndex;
        return [$bgColor, $textColor];
    }
}
