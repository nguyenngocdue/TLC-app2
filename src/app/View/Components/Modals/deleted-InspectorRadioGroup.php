<?php

namespace App\View\Components\Modals;

use App\Models\User;
use Illuminate\View\Component;

class InspectorRadioGroup extends Component
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
        $inspectors = User::getAllInspector();
        return view('components.modals.inspector-radio-group', [
            'inspectors' => $inspectors,
        ]);
    }
}
