<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use Illuminate\View\Component;

class Dropdownmulti extends Component
{
    public function __construct(private $colName, private $idItems, private $action, private $tablePath, private $labelName)
    {
    }

    public function render()
    {
        $span = 6;
        $action = $this->action;
        $colName = $this->colName;
        $idItems = $this->idItems;
        $labelName = $this->labelName;


        $dataSource = Helper::getDatasource(0,  new  $this->tablePath, $colName);
        if (!is_array($dataSource)) {
            $message =  "Not found ColumnName \"" . $colName . "\" in eloquentParams (in Model).";
            $type = 'warning';
            return view('components.feedback.alert')->with(compact('message', 'type'));
        }

        return view('components.controls.dropdownmulti')->with(compact('dataSource', 'colName', 'idItems', 'action', 'span', 'labelName'));
    }
}
