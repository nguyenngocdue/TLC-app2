<?php

namespace App\View\Components\Calendar;

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
        return view('components.calendar.full-calendar', [
            'modalId' => 'calendar001',
            'timesheetableType' => $this->timesheetableType,
            'timesheetableId' => $this->timesheetableId,
        ]);
    }
}
