<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Checkbox extends Component
{
    public function __construct(private $id, private $colName, private $idItems, private $action, private $tablePath, private $labelName, private $type)
    {
    }

    public function render()
    {

        $span = 6;
        $action = $this->action;
        $colName = $this->colName;
        $idItems = $this->idItems;
        $labelName = $this->labelName;
        $modelPath = $this->tablePath;
        $type = $this->type;

        $dataSource = Helper::getDataSource($modelPath, $colName, $type);
        if (is_null($dataSource) || gettype($dataSource) === 'string') {
            $message =  "Not found control_name \"" . $colName . "\" in  Manage Relationships.";
            $type = 'warning';
            return "<x-feedback.alert message='$message' type='$type' />";
            // return view('components.feedback.alert')->with(compact('message', 'type'));
        }
        return view('components.controls.checkbox')->with(compact('dataSource', 'colName', 'idItems', 'action', 'span', 'labelName'));
    }
}
