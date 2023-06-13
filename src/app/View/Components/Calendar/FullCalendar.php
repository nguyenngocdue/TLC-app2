<?php

namespace App\View\Components\Calendar;

use App\Utils\Support\CurrentUser;
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
        private $readOnly = false,
        private $arrHidden = [],
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
        $token = CurrentUser::getTokenForApi();
        return view('components.calendar.full-calendar', [
            'modalId' => 'calendar001',
            'timesheetableType' => $this->timesheetableType,
            'timesheetableId' => $this->timesheetableId,
            'apiUrl' => $this->apiUrl,
            'token' => $token,
            'readOnly' => $this->readOnly,
            'arrHidden' => $this->arrHidden,
        ]);
    }
}
