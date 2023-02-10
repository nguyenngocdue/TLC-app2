<?php

namespace App\View\Components\Control\renderer;

use Illuminate\View\Component;

class Dropdown extends Component
{

    public function __construct(
        private $dataSource = [],
        private $name = 'name',
        private $itemsSelected = []
    ) {
    }


    public function render()
    {

        return view('components.control.renderer.dropdown', [
            'dataSource' =>  $this->dataSource,
            'name' => $this->name,
            'itemsSelected' => $this->itemsSelected
        ]);
    }
}
