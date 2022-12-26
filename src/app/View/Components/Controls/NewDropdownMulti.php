<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use Illuminate\View\Component;

class NewDropdownMulti extends Component
{

    public function __construct(
        private $colName,
        private $action,
        private $modelPath,
        private $label,
        private $type,
        private $id,
    ) {
        //
    }

    public function render()
    {
        $span = 6;
        $action = $this->action;
        $colName = $this->colName;
        $label = $this->label;
        $modelPath = $this->modelPath;
        $type = $this->type;


        $allFields = Helper::getDataDbByName('fields', 'name', 'id');
        $fixColName = str_replace('()', '', $colName);
        if (!isset($allFields[$fixColName])) return "<x-feedback.alert message='Not found control_name \"$fixColName\" in  Fields.' type='warning' />";
        $idsChecked = is_null($item =  $modelPath::find($this->id)) ? [] : $item->getCheckedByField($allFields[$fixColName], '')->pluck('id')->toArray();

        $dataSource = Helper::getDataSourceByManyToMany($modelPath, $colName, $type);
        if (is_null($dataSource) || gettype($dataSource) === 'string') return "<x-feedback.alert message='Not found control_name \"$colName\" in  Fields.' type='warning' />";
        return view('components.controls.new-dropdown-multi')->with(compact('dataSource', 'colName', 'idsChecked', 'action', 'span', 'label'));
    }
}
