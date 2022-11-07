<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Radio extends Component
{
    public function __construct(private $id, private $colName, private $tablePath, private $action, private $labelName)
    {
    }

    public function render()
    {
        $span = 6;
        $action = $this->action;
        $colName = $this->colName;
        $labelName = $this->labelName;

        $dataSource = Helper::getDatasource($this->id, new  $this->tablePath, $colName);
        if (!is_array($dataSource)) {
            $message =  "Not found ColumnName \"" . $colName . "\" in eloquentParams (in Model).";
            $type = 'warning';
            return view('components.feedback.alert')->with(compact('message', 'type'));
        }

        return view('components.controls.radio')->with(compact('dataSource', 'colName', 'span', 'action', 'labelName'));
    }
}
