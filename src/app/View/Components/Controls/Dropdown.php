<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Models\User;
use App\Models\Zunit_test_9;
use App\Utils\Support\Listeners;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

use function PHPUnit\Framework\once;

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
        $dataListenTrigger = $this->getDataTrigger($modelPath, $colName); // k

        return view('components.controls.dropdown')->with(compact('dataSource', 'colName', 'action', 'label', 'currentEntity', 'dataListenTrigger', 'listenersJson'));
    }

    public function getDataTrigger($modelPath, $colName)
    {
        $dataTarget = [];
        $instance = new $modelPath;
        $modelPaths_colNames =  (array_column(array_values($instance->eloquentParams), 2, 1));
        foreach ($modelPaths_colNames as $modelPath => $value) {
            $dataTarget[$value] = Helper::getDataFromPathModel($modelPath)->toArray();
        };
        return $dataTarget;
    }
}
