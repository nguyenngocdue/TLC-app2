<?php

namespace App\View\Components\Reports;

use App\Utils\Support\Report;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class Dropdown7 extends Component
{

    public function __construct(
        private $name = 'No name',
        private $itemsSelected = [],
        private $title = "No title",
        private $allowClear = false,
    ) {
    }


    public function render()
    {
        $selected = $this->itemsSelected[$this->name];
        $title = $this->title;
        $viewName = Report::getViewName($this->name);
        $str = "<span class='px-1 '>$title</span>
                <x-reports.modals.$viewName name='$this->name' selected='$selected' allowClear='$this->allowClear' />";
        echo  Blade::render($str);
    }
}
