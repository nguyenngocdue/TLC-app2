<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use Illuminate\View\Component;

class DropdownMulti extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $colName,
        private $idItems,
        private $action,
        private $modelPath,
        private $label,
        private $type,

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
        $span = 6;
        $action = $this->action;
        $colName = $this->colName;
        $idItems = $this->idItems;
        $label = $this->label;
        $modelPath = $this->modelPath;
        $type = $this->type;

        $dataSource = Helper::getDataSourceByManyToMany($modelPath, $colName, $type);
        if (is_null($dataSource) || gettype($dataSource) === 'string') {
            $message =  "Not found control_name \"" . $colName . "\" in  Manage Relationships.";
            return "<x-feedback.alert message='$message' type='warning' />";
        }
        return view('components.controls.dropdown-multi')->with(compact('dataSource', 'colName', 'idItems', 'action', 'span', 'label'));
    }
}
