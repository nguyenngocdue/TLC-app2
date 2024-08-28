<?php

namespace App\View\Components\Calendar;

use Illuminate\View\Component;

class SidebarCalendar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $id = null,
        private $model = null,
        private $readOnly = false,
        private $type = null,
        private $timesheetableType = null,
        private $timesheetableId = null,
        private $hiddenCalendarHeader = false,
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
        return view('components.calendar.sidebar-calendar', [
            'id' => $this->id,
            'model' => $this->model,
            'readOnly' => $this->readOnly,
            'type' => $this->type,
            'timesheetableType' => $this->timesheetableType,
            'timesheetableId' => $this->timesheetableId,
        ]);
    }
}
