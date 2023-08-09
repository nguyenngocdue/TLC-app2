<?php

namespace App\View\Components\Print;

use Illuminate\View\Component;

class Header6 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $page = null,
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
        
        return view('components.print.header6', [
            'dataSource' => config("company.letter_head"),
            'page' => $this->page,
        ]);
    }
}
