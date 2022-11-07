<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Headeralertvalidation extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private  $strProps)
    {
        //
    }

    public function render()
    {

        $props = $this->strProps;
        return view('components.controls.headeralertvalidation')->with(compact('props'));
    }
}
