<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Radio extends Component
{
    public function __construct(private $id, private $colName, private $type, private $modelPath, private $action, private $label)
    {
    }

    public function render()
    {
        $span = 6;
        $action = $this->action;
        $colName = $this->colName;
        $label = $this->label;
        $modelPath = $this->modelPath;
        $type = $this->type;

        $dataSource = Helper::getDataSource($modelPath, $colName, $type);
        $currentEntity = Helper::getItemModel($this->type, $this->id) ?? [];

        if (is_null($dataSource) || gettype($dataSource) === 'string') {
            $message =  "Not found ColumnName \"" . $colName . "\" in eloquentParams (in $modelPath Model).";
            return "<x-feedback.alert message='$message' type='warning' />";
        }
        return view('components.controls.radio')->with(compact('dataSource', 'currentEntity', 'colName', 'span', 'action', 'label'));
    }
}
