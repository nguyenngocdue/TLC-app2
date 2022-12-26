<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use Illuminate\View\Component;

class NewCheckbox extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $id,
        private $colName,
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
        $label = $this->label;
        $modelPath = $this->modelPath;
        $type = $this->type;



        $allFields = Helper::getDataDbByName('fields', 'name', 'id');
        $keyColName = str_replace('()', '', $colName);
        if (!isset($allFields[$keyColName])) return "<x-feedback.alert message='Not found control_name \"$keyColName\" in  Fields.' type='warning' />";
        $idsChecked = is_null($item =  $modelPath::find($this->id)) ? [] : $item->getCheckedByField($allFields[$keyColName], '')->pluck('id')->toArray();

        $dataSource = Helper::getDataSourceByManyToMany($modelPath, $colName, $type);
        if (is_null($dataSource) || gettype($dataSource) === 'string') return "<x-feedback.alert message='Not found control_name \"$colName\" in  Fields.' type='warning' />";

        return view('components.controls.new-checkbox')->with(compact('dataSource', 'colName', 'idsChecked', 'action', 'span', 'label'));
    }
}
