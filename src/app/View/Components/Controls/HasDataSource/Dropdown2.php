<?php

namespace App\View\Components\Controls\HasDataSource;

use Illuminate\View\Component;

class Dropdown2 extends Component
{
    use HasDataSource;
    public function __construct(
        private $type,
        private $name,
        private $selected,
        private $multiple = false,
    ) {
        //
    }

    public function render()
    {
        $eloquentOrOracy = $this->multiple ? "oracyParams" : "eloquentParams";
        $dataSource = $this->getDataSource($eloquentOrOracy);
        $warning = $this->warningIfDataSourceIsEmpty($dataSource);
        if ($warning) return $warning;

        $selected = ($this->selected[0] != '[') ? "[" . $this->selected . "]" : $this->selected;

        return view('components.controls.has-data-source.dropdown2', [
            'dataSource' => $dataSource,
            'name' => $this->name,
            'selected' => $selected,
            'multiple' => $this->multiple,
        ]);
    }
}
