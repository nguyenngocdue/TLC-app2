<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Models\Zunit_test_1;
use Illuminate\View\Component;

class Checkbox extends Component
{

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

    public function render()
    {
        $span = 6;
        $action = $this->action;
        $colName = $this->colName;
        $label = $this->label;
        $modelPath = $this->modelPath;
        $type = $this->type;


        $allFields = Helper::getDataDbByName('fields', 'name', 'id');
        $dataSource = Helper::getDataSourceByManyToMany($modelPath, $colName, $type);

        $idsChecked = $modelPath::find($this->id)->getCheckedByField($allFields[$colName], '')->pluck('id')->toArray();

        if (is_null($dataSource) || gettype($dataSource) === 'string') {
            $message =  "Not found control_name \"" . $colName . "\" in  Manage Relationships.";
            return "<x-feedback.alert message='$message' type='warning' />";
        }
        return view('components.controls.checkbox')->with(compact('dataSource', 'colName', 'idsChecked', 'action', 'span', 'label'));
    }
}
