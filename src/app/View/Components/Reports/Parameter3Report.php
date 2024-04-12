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
        private $hiddenItems = [],
        private $columns = [],
        private $routeName = '',
        private $entity = '',
        private $typeReport = '',
        private $modeOption = '',
        private $hasListenTo = false,
        private $optionPrint = '',
        private $childrenMode = '',
        private $type = '',
        private $forwardToMode = '',
    ) {
        //
    }

    public function render()
    {
        return view('components.reports.parameter3-report', [
            'itemsSelected' => $this->itemsSelected,
            'hiddenItems' => $this->hiddenItems,
            'routeName' => $this->routeName,
            'entity' => $this->entity,
            'typeReport' => $this->typeReport,
            'columns' => $this->columns,
            'modeOption' => $this->modeOption,
            'hasListenTo' => $this->hasListenTo,
            'optionPrint' => $this->optionPrint,
            'childrenMode' => $this->childrenMode,
            'forwardToMode' => $this->forwardToMode,
            'type' => $this->type,
        ]);
    }
}
