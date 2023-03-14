<?php

namespace App\View\Components\Reports;

use Illuminate\View\Component;

class Dropdown6 extends Component
{

    public function __construct(
        private $dataSource = [],
        private $name = 'No name',
        private $itemsSelected = [],
        private $title = "No title",
        private $allowClear = false,
        private $formName = ''
    ) {
    }


    public function render()
    {
        return view('components.reports.dropdown6', [
            'dataSource' =>  $this->dataSource,
            'name' => $this->name,
            'itemsSelected' => $this->itemsSelected,
            'name' => $this->name,
            'title' => $this->title,
            'allowClear' => $this->allowClear,
            'formName' => $this->formName

        ]);
    }
}
