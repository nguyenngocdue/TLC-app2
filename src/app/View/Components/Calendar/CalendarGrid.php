<?php

namespace App\View\Components\Calendar;

use Illuminate\View\Component;

class CalendarGrid extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $readOnly = true,
        private $timesheetableType = null,
        private $timesheetableId = null,
        private $apiUrl = null,
        private $arrHidden = null,
        private $type = null,
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
        return view('components.calendar.calendar-grid', [
            'readOnly' => $this->readOnly,
            'timesheetableType' => $this->timesheetableType,
            'timesheetableId' => $this->timesheetableId,
            'apiUrl' => $this->apiUrl,
            'arrHidden' => $this->arrHidden,
            'type' => $this->type,
        ]);
    }
}
