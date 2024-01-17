<?php

namespace App\View\Components\Reports;

use Illuminate\View\Component;
use Illuminate\Support\Arr;


abstract class ParentParamReports extends Component
{

    use TraitParentIdParamsReport;
    use TraitParentIdHasListenToParamsReport;
    #use TraitParentIdSingleParamsReport;
    abstract protected function getDataSource();
    protected $referData = '';
    protected $referData1 = '';
    public function __construct(
        private $name ="",
        private $selected = "",
        private $multiple = false,
        private $readOnly = false,
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
        private $allowClear = false,
        private $hasListenTo = false,
        private $showNumber = false,
        private $showNow = false,
        private $type = '',
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }
    public function getType()
    {
        return $this->type;
    }

    public function getParams(){
        return [
            'showNumber' => $this->showNumber,
            'showNow' => $this->showNow,
        ];
    }

    public function hasListenTo() {
        return $this->hasListenTo;
    }

    public function render()
    {
        $hasListenTo = $this->hasListenTo;
        if($hasListenTo) {
            // dump($this->selected);
            $params = $this->getListenToParamsReport();
            return view('components.controls.has-data-source.' . $this->control, $params);
        } else {
            $params = $this->getParamsReport();
            return view('components.controls.has-data-source.'.$this->control, $params);
        }
    }
}
