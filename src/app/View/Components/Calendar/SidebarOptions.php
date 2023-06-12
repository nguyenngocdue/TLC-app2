<?php

namespace App\View\Components\Calendar;

use Illuminate\View\Component;

class SidebarOptions extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $id = null, private $model = null)
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
        $model = $this->model::findOrFail($this->id);
        return view('components.calendar.sidebar-options');
    }
}
