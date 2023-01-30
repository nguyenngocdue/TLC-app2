<?php

namespace App\View\Components\Controls\HasDataSource;

use Illuminate\View\Component;

class Radio2 extends Component
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
        $dataSource = $this->getDataSource('eloquentParams');
        $warning = $this->warningIfDataSourceIsEmpty($dataSource);
        if ($warning) return $warning;

        return view('components.controls.has-data-source.radio2', [
            'dataSource' => $dataSource,
            'span' => $this->getSpan(),
            'name' => $this->name,
            'selected' => $this->selected,
        ]);
    }
}
