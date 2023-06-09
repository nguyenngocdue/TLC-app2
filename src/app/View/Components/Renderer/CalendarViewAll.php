<?php

namespace App\View\Components\Renderer;

use App\Models\Hr_timesheet_officer;
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
        $allTimesheet = Hr_timesheet_officer::all()->map(function ($item) {
            // $item['day_value'] = $this->getWeekByDay($item->week)[0];
            $item['week_value'] = $this->getWeekByDay($item->week)[1];
            // $item['month_value'] = $this->getWeekByDay($item->week)[2];
            $item['year_value'] = $this->getWeekByDay($item->week)[3];
            $item['url'] = route($item->getTable() . '.edit', $item->id);
            return $item;
        })->groupBy('year_value');
        return view('components.renderer.calendar-view-all', [
            'allTimesheet' => $allTimesheet,
        ]);
    }
    private function getWeekByDay($day)
    {
        $date = Carbon::parse($day);
        return [$date->day, $date->weekOfYear, $date->month, $date->year];
    }
}
