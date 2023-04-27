<?php

namespace App\View\Components\AdvancedFilter;

use App\Utils\ClassList;
use Illuminate\View\Component;

class Toggle3 extends Component
{
    public function __construct(
        private $name,
        private $value,
        private $dataSource = null,
    ) {
    }

    public function render()
    {
        $dataSource = $this->dataSource;
        if (!$dataSource) {
            $dataSource = [
                '' => "null",
                'True' => 1,
                'False' => 0,
            ];
        }
        // dd($this->value);
        return view('components.advanced-filter.toggle3', [
            'name' => $this->name,
            'selected' => $this->value,
            'dataSource' => $dataSource,
            'classList' => ClassList::DROPDOWN,
        ]);
    }
}
