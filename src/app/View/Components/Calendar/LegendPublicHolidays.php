<?php

namespace App\View\Components\Calendar;

use App\Http\Resources\HolidayCollection;
use App\Models\Workplace;
use App\Utils\Constant;
use App\Utils\GridCss;
use App\Utils\Support\Calendar;
use Illuminate\View\Component;

class LegendPublicHolidays extends Component
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
        $workplaces = Workplace::all()->pluck('id', 'name')->toArray();
        $workplaces['Workplaces'] = [1, 2, 3];
        $match = [];
        foreach ($workplaces as $key => $value) {
            $match[] = [
                "name" => $key,
                "color" => HolidayCollection::getBackGroundColorByWorkplaceId($value, true)
            ];
        }
        $count = count($match);
        $gridCss = GridCss::getGridCss($count);
        return view('components.calendar.legend-public-holidays', [
            'dataSource' => $match,
            'title' => "Public Holidays",
            'gridCss' => $gridCss,
        ]);
    }
}
