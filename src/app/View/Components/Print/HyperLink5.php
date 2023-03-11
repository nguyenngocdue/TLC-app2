<?php

namespace App\View\Components\Print;

use Illuminate\View\Component;

class HyperLink5 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $label,
        private $href,
    ) {
        // dump($this->cell);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $title = $this->href ? $this->href : 'Link not found';
        $isNotFound = $this->href ? true : false;
        return view('components.print.hyper-link5', [
            'title' => $title,
            'href' => $this->href,
            'label' => $this->label,
            'isNotFound' => $isNotFound,
        ]);
    }
}
