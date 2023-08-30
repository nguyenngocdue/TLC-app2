<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewEditFunctions;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class SidebarFilter extends Component
{
    use TraitViewEditFunctions;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $readOnly = false,
        private $type = null,
        private $timesheetableType = null,
        private $timesheetableId = null,
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
        [$valueFiltersTask] = $this->getUserSettingsViewEditCalendar();
        $owner = $this->getSheetOwner($this->timesheetableType, $this->timesheetableId);
        $selectedUserDisciplineId = ($owner) ? $owner->discipline : null;
        return view('components.calendar.sidebar-filter', [
            'selectedUserDisciplineId' => $selectedUserDisciplineId,
            'readOnly' => $this->readOnly,
            'valueFiltersTask' => $valueFiltersTask,
        ]);
    }
}
