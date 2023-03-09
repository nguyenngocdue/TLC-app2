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
        private $columns = [],
        private $routeName = '',
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
            'routeName' => $this->routeName,
            'entity' => $this->entity,
            'typeReport' => $this->typeReport,
            'columns' => $this->columns
        ]);
    }
}
