<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class PerPageReport extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $typeReport = '',
        private $route = '',
        private $pageLimit = '',
        private $entity = '',
        private $routeName = ''
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
        return view('components.form.per-page-report', [
            'typeReport' => $this->typeReport,
            'route' => $this->route,
            'pageLimit' => $this->pageLimit,
            'entity' => $this->entity,
            'routeName' => $this->routeName
        ]);
    }
}
