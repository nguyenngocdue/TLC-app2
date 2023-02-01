<?php

namespace App\View\Components\Controls\HasDataSource;

use Illuminate\View\Component;

class Checkbox2 extends Component
{
    use HasDataSource;
    public function __construct(
        private $type,
        private $name,
        private $selected,
    ) {
        //
    }

    public function render()
    {
        $dataSource = $this->getDataSourceEOO('oracyParams');
        $warning = $this->warningIfDataSourceIsEmpty($dataSource);
        if ($warning) return $warning;

        $selected = json_decode($this->selected);
        $selected = is_null($selected) ? [] : $selected;

        return view('components.controls.has-data-source.checkbox2', [
            'dataSource' => $dataSource,
            'span' => $this->getSpan(),
            'name' => $this->name,
            'selected' => $selected,
        ]);
    }
}
