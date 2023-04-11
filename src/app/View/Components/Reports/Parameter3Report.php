<?php

namespace App\View\Components\Reports;

use Illuminate\View\Component;

class Parameter3Report extends Component
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
        private $modeOption = '',
    ) {
        //
    }

    public function render()
    {
        // dd($this->itemsSelected);
        return view('components.reports.parameter3-report', [
            'itemsSelected' => $this->itemsSelected,
            'dataSource' => $this->dataSource,
            'hiddenItems' => $this->hiddenItems,
            'routeName' => $this->routeName,
            'entity' => $this->entity,
            'typeReport' => $this->typeReport,
            'columns' => $this->columns,
            'modeOption' => $this->modeOption,
        ]);
    }
}
