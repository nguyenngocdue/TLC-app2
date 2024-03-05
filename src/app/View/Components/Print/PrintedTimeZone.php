<?php

namespace App\View\Components\Print;

use App\Utils\Support\CurrentUser;
use DateTime;
use DateTimeZone;
use Illuminate\View\Component;

class PrintedTimeZone extends Component
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
        $currentUser = CurrentUser::get();
        $nameCurrentUser = $currentUser->name;
        $timeZoneCurrentUser = $currentUser->time_zone ?? 7;
        $timezone = $this->formatTimeZone($timeZoneCurrentUser);
        $timeNow = new DateTime('now', new DateTimeZone($timezone));
        $timeNow = $timeNow->format('d/m/Y H:i');
        return view('components.print.printed-time-zone', [
            'name' => $nameCurrentUser,
            'timezone' => $timezone,
            'timeNow' => $timeNow,
        ]);
    }
    private function formatTimeZone($timezone)
    {
        return preg_replace('/(\d+)/', '+0$1:00', $timezone);
    }
}
