<?php

namespace App\View\Components\Calendar;

use App\Utils\System\GetSetCookie;
use Illuminate\View\Component;

class FullCalendar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $timesheetableType,
        private $timesheetableId,
        private $apiUrl,
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
        $token = '';
        if (GetSetCookie::hasCookie('tlc_token')) {
            $token = GetSetCookie::getCookie('tlc_token');
        }
        return view('components.calendar.full-calendar', [
            'modalId' => 'calendar001',
            'timesheetableType' => $this->timesheetableType,
            'timesheetableId' => $this->timesheetableId,
            'apiUrl' => $this->apiUrl,
            'token' => $token,
        ]);
    }
}
