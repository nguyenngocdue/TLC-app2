<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Refresh extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type = '',
        private $route = '',
        private $class = '',
        private $valueRefresh = null,
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
        return view('components.form.refresh', [
            'type' => $this->type,
            'route' => $this->route,
            'class' => $this->class,
            'valueRefresh' => $this->valueRefresh,
        ]);
    }
}
