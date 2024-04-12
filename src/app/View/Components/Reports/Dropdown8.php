<?php

namespace App\View\Components\Reports;

use Illuminate\View\Component;

class Dropdown8 extends Component
{

    public function __construct(
        private $dataSource = [],
        private $name = 'No name',
        private $itemsSelected = [],
        private $title = "No title",
        private $allowClear = false,
        private $formName = '',
        private $typeReport = '',
        private $entity = '',
        private $routeName = '',
        private $modeOption = '',
        private $forwardToMode = '',

    ) {
    }


    public function render()
    {
        // dd($this->name);
        return view('components.reports.dropdown8', [
            'dataSource' =>  $this->dataSource,
            'name' => $this->name,
            'itemsSelected' => $this->itemsSelected,
            'name' => $this->name,
            'title' => $this->title,
            'allowClear' => $this->allowClear,
            'formName' => $this->formName,
            'typeReport' => $this->typeReport,
            'routeName' => $this->routeName,
            'modeOption' => $this->modeOption,
            'entity' => $this->entity,
        ]);
    }
}
