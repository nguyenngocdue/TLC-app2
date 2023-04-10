<?php

namespace App\View\Components\Reports;

use Illuminate\View\Component;

class Parameter2Report extends Component
{

    public function __construct(
        private $itemsSelected = [],
        private $dataSource = [],
        private $hiddenItems = [],
        private $columns = [],
        private $routeName = '',
        private $entity = '',
        private $typeReport = '',
        private $modeOption = '',
        private $getSettingParams = [],
    ) {
        //
    }

    public function render()
    {
        // dd($this->itemsSelected);
        return view('components.reports.parameter2-report', [
            'itemsSelected' => $this->itemsSelected,
            'dataSource' => $this->dataSource,
            'hiddenItems' => $this->hiddenItems,
            'routeName' => $this->routeName,
            'entity' => $this->entity,
            'typeReport' => $this->typeReport,
            'columns' => $this->columns,
            'modeOption' => $this->modeOption,
            'getSettingParams' => $this->getSettingParams
        ]);
    }
}
