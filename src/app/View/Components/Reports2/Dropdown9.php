<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;

class Dropdown9 extends Component
{
    //for report 2
    public function __construct(
        private $dataSource = [],
        private $name = 'No name',
        private $currentParams = [],
        private $title = "No title",
        private $allowClear = false,
        private $routeName = '',

    ) {
    }


    public function render()
    {
        return view('components.reports2.dropdown9', [
            'dataSource' =>  $this->dataSource,
            'name' => $this->name,
            'currentParams' => $this->currentParams,
            'name' => $this->name,
            'title' => $this->title,
            'allowClear' => $this->allowClear,
            'routeName' => $this->routeName,
        ]);
    }
}
