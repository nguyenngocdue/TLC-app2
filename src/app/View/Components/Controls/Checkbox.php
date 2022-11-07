<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Checkbox extends Component
{
    public function __construct(private $id, private $colName, private $idItems, private $action, private $tablePath, private $labelName)
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

        $dataSource = Helper::getDatasource($modelPath, $colName);

        if (is_null($dataSource)) {
            $message =  "Not found ColumnName \"" . $colName . "\" in eloquentParams (in Model).";
            $type = 'warning';
            return view('components.feedback.alert')->with(compact('message', 'type'));
        }
        return view('components.controls.checkbox')->with(compact('dataSource', 'colName', 'idItems', 'action', 'span', 'labelName'));
    }
}
