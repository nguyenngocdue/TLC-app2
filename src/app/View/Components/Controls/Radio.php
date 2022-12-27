<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Utils\Support\Relationships;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Radio extends Component
{
    public function __construct(
        private $id,
        private $colName,
        private $type,
        private $modelPath,
        private $action,
        private $label,
    ) {
    }

    public function render()
    {
        $action = $this->action;
        $colName = $this->colName;
        $label = $this->label;
        $modelPath = $this->modelPath;
        $type = $this->type;

        $dataSource = Helper::getDataSource($modelPath, $colName, $type);
        // dd($dataSource);
        $currentEntity = Helper::getItemModel($this->type, $this->id) ?? [];
        if (is_null($dataSource) || gettype($dataSource) === 'string') {
            $message =  "Not found ColumnName \"" . $colName . "\" in eloquentParams (in $modelPath Model).";
            return "<x-feedback.alert message='$message' type='warning' />";
        }

        $span = Helper::setColSpan($colName, $type);
        return view('components.controls.radio')->with(compact('dataSource', 'currentEntity', 'colName', 'span', 'action', 'label'));
    }
}
