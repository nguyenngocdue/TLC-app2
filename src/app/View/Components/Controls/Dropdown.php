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


        $dataSource = Helper::getDatasource($this->id, new $this->tablePath, $colName);
        if (!is_array($dataSource)) {
            $message =  "Not found ColumnName \"" . $colName . "\" in eloquentParams (in Model).";
            $type = 'warning';
            return view('components.feedback.alert')->with(compact('message', 'type'));
        }
        return view('components.controls.dropdown')->with(compact('dataSource', 'colName', 'action', 'labelName'));
    }
}
