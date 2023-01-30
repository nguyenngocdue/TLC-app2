<?php

namespace App\View\Components\Controls\HasDataSource;

use Illuminate\View\Component;

class Checkbox2 extends Component
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
        return view('components.controls.has-data-source.checkbox2');
    }
}
