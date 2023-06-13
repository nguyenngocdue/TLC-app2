<?php

namespace App\View\Components\Calendar;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class SidebarFilter extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $readOnly = false,
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
        $user = CurrentUser::get();
        $discipline = ($user) ? $user->discipline : null;
        return view('components.calendar.sidebar-filter', [
            'discipline' => $discipline,
            'readOnly' => $this->readOnly,
        ]);
    }
}
