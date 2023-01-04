<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Models\User;
use App\Models\Zunit_test_9;
use App\Utils\Support\Listeners;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Dropdown extends Component
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

        $dataSource = Helper::getDataSourceHasKeyTableName($modelPath, $colName, $type);
        $currentEntity = Helper::getItemModel($this->type, $this->id) ?? [];

        if (is_null($dataSource) || gettype($dataSource) === 'string') {
            $message =  "Not found ColumnName \"" . $colName . "\" in eloquentParams (in $modelPath Model).";
            return "<x-feedback.alert message='{$message}' type='warning' />";
        }

        $listenersJson = Listeners::getAllOf($type);
        $dataListenTrigger = $this->getDataFromListenersJson('column_name', $listenersJson, $modelPath, $type);
        $dataListenToField = $this->getDataFromListenersJson('listen_to_fields', $listenersJson, $modelPath, $type);
        return view('components.controls.dropdown')->with(compact('dataListenToField', 'dataSource', 'colName', 'action', 'label', 'currentEntity', 'dataListenTrigger', 'listenersJson'));
    }

    public function getDataFromListenersJson($keyName = 'column_name', $listenersJson, $modelPath, $type)
    {
        $dataTarget = [];
        foreach ($listenersJson as $value) {
            $listen_to = $value[$keyName];
            $val = Helper::getDataSource($modelPath, $listen_to, $type);
            $dataTarget[$listen_to] = $val->toArray();
        };
        return $dataTarget;
    }
}
