<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;

class Radio extends Component
{
    public function __construct(
        private $id,
        private $colName,
        private $type,
        private $modelPath,
        private $label,
    ) {
    }

    public function render()
    {
        $action = CurrentRoute::getControllerAction();
        $colName = $this->colName;
        $label = $this->label;
        $modelPath = $this->modelPath;
        $type = $this->type;

        $dataSource = Helper::getDataSourceHasKeyTableName($modelPath, $colName, $type);
        $currentEntity = Helper::getItemModel($this->type, $this->id) ?? [];
        if (is_null($dataSource) || gettype($dataSource) === 'string') {
            $message =  "Not found ColumnName \"" . $colName . "\" in eloquentParams (in $modelPath Model).";
            return "<x-feedback.alert message='$message' type='warning' />";
        }

        $span = Helper::getColSpan($colName, $type);
        return view('components.controls.radio')->with(compact('dataSource', 'currentEntity', 'colName', 'span', 'action', 'label'));
    }
}
