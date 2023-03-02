<?php

namespace App\View\Components\Form;

use AWS\CRT\HTTP\Request;
use Illuminate\View\Component;

class ParameterReport extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $itemsSelected = [],
        private $dataSource = [],
        private $hiddenItems = [],
        private $route = '',
        private $entity = '',
        private $typeReport = '',


    ) {
        //
    }

    public function render()
    {
        return view('components.form.parameter-report', [
            'itemsSelected' => $this->itemsSelected,
            'dataSource' => $this->dataSource,
            'hiddenItems' => $this->hiddenItems,
            'route' => $this->route,
            'entity' => $this->entity,
            'typeReport' => $this->typeReport
        ]);
    }
}
