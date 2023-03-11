<?php

namespace App\View\Components\Form;

use App\Utils\ClassList;
use Illuminate\View\Component;

class PerPage extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type = '',
        private $route = '',
        private $perPage = '',
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

        return view('components.form.per-page', [
            'type' => $this->type,
            'route' => $this->route,
            'perPage' => $this->perPage,
            'classList' => ClassList::DROPDOWN,
        ]);
    }
}
