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

        $dataSource = Helper::getDatasource($modelPath, $colName);
        $currentEntity = is_null($modelPath::find($this->id)) ? "" : $modelPath::find($this->id)->getAttributes();

        // dd($currentEntity, $dataSource, $modelPath);

        if (is_null($dataSource)) {
            $message =  "Not found ColumnName \"" . $colName . "\" in eloquentParams (in Model).";
            $type = 'warning';
            return view('components.feedback.alert')->with(compact('message', 'type'));
        }
        return view('components.controls.dropdown')->with(compact('dataSource', 'colName', 'action', 'labelName', 'currentEntity'));
    }
}
