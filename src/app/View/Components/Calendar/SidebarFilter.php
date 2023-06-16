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
        $user = CurrentUser::get();
        $discipline = ($user) ? $user->discipline : null;
        return view('components.calendar.sidebar-filter', [
            'discipline' => $discipline,
            'readOnly' => $this->readOnly,
            'valueFiltersTask' => $valueFiltersTask,
        ]);
    }
}
