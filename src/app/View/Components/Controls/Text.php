<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Text extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    private $columnName;
    private $valColName;
    public function __construct($columnName, $valColName)
    {
        $this->columnName = $columnName;
        $this->valColName = $valColName;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $columnName = $this->columnName;
        $valColName = $this->valColName;
        return view('components.controls.text')->with(compact('columnName', 'valColName'));
    }
}
