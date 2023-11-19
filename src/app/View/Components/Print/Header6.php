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
        private $itemsShow = [],
        private $class = "",
        private $classImg = "",
        private $dimensionImg = "",
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
            'itemsShow' => $this->itemsShow,
            'class' => $this->class,
            'classImg' => $this->classImg,
            'dimensionImg' => $this->dimensionImg
        ]);
    }
}
