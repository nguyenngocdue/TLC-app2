<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Dropdown3 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $relationships = '',
        private $name = '',
        private $valueSelected = null,
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
        $dataRelationShips = $this->relationships;
        $params = $dataRelationShips['eloquentParams'] ?? $dataRelationShips['oracyParams'];
        $dataSource = (new $params[1])::all();
        return view('components.controls.dropdown3', [
            'dataSource' => $dataSource,
            'name' =>  $this->name,
            'valueSelected' => $this->valueSelected,
        ]);
    }
}
