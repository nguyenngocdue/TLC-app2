<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use Illuminate\View\Component;

class Dropdown extends Component
{
    public function __construct(private $id, private $colName, private $type, private $tablePath, private $action, private $labelName)
    {
    }

    public function render()
    {
        $action = $this->action;
        $colName = $this->colName;
        $labelName = $this->labelName;
        $modelPath = $this->tablePath;
        $type = $this->type;

        $dataSource = Helper::getDataSource($modelPath, $colName, $type);
        $currentEntity = is_null($modelPath::find($this->id)) ? "" : $modelPath::find($this->id)->getAttributes();

        if (is_null($dataSource) || gettype($dataSource) === 'string') {
            $message =  "Not found ColumnName \"" . $colName . "\" in eloquentParams (in $modelPath Model).";
            return "<x-feedback.alert message='{$message}' type='warning' />";
        }
        return view('components.controls.dropdown')->with(compact('dataSource', 'colName', 'action', 'labelName', 'currentEntity'));
    }
}
