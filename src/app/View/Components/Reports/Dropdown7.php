<?php

namespace App\View\Components\Reports;

use App\Utils\Support\Report;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class Dropdown7 extends Component
{

    public function __construct(
        private $name = 'No name',
        private $itemsSelected = [],
        private $title = "No title",
        private $allowClear = false,
        private $multiple = false,
        private $hasListenTo = false,
        private $infoParam = [],
        private $type ="",
        private $showNumber=false,
        private $showNow=false,
    ) {
    }


    public function render()
    {
        // dd($this->name);
        $selected = $this->itemsSelected[$this->name] ?? '';
        $title = $this->title;
        $viewName = Report::getViewName($this->name);
        $selected = is_array($selected) ? json_encode($selected) : $selected;

        $infoParam = $this->infoParam;
        $strStar = isset($infoParam['validation']) && $infoParam['validation'] ? "<span class='text-red-400'>*</span>": "";
        $info = "";
        if(!App::isProduction()) {
            $info = "dataIndex: $this->name";
            $info .="\nviewName: $viewName";
            $info .="\nmultiple: ".($this->multiple ? 'true' : 'false');
            $info .="\nhasListenTo: ".($this->hasListenTo ? 'true' : 'false');
            $info .="\nshowNow: ". ($this->showNow ? "true" : 'false');
        }
        // dump($selected);
        $str = "<span class='px-1' title='$info'>$title $strStar</span>
        
        <x-reports.mode-params.$viewName showNow='$this->showNow' showNumber='$this->showNumber' name='$this->name' selected='$selected' allowClear='$this->allowClear' multiple='$this->multiple' hasListenTo='$this->hasListenTo' type='$this->type' />";
        echo  Blade::render($str);
    }   
}
