<?php

namespace App\View\Components\Calendar;

use App\Utils\Support\CurrentUser;
use Carbon\Carbon;
use Illuminate\View\Component;

class FullCalendarPublicHolidays extends Component
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
        $apiUrl = route('public-holidays-data.index');
        $currentYear = Carbon::now()->year;
        return view('components.calendar.full-calendar-public-holidays',[
            'apiUrl' => $apiUrl,
            'token' => $token,
            'currentYear' => $currentYear
        ]);
    }
}
