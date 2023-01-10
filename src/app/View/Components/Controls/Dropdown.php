<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Models\User;
use App\Models\Zunit_test_9;
use App\Utils\Support\Entities;
use App\Utils\Support\Listeners;
use App\Utils\Support\Props;
use App\Utils\Support\Relationships;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
use Illuminate\Support\Str;


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

        $currentEntity = Helper::getItemModel($this->type, $this->id) ?? [];
        // $dataSource = Helper::getDataSourceHasKeyTableName($modelPath, $colName, $type);

        // if (is_null($dataSource) || gettype($dataSource) === 'string') {
        //     $message =  "Not found ColumnName \"" . $colName . "\" in eloquentParams (in $modelPath Model).";
        //     return "<x-feedback.alert message='{$message}' type='warning' />";
        // }
        // dump($type);


        $listenersJson = Listeners::getAllOf($type);

        $instance = new $modelPath;
        $eloquentParams = array_values($instance->eloquentParams);
        $colNames_modelPaths =  (array_column($eloquentParams, 1, 2));

        $props = Props::getAllOf($type);
        $colNamesHaveDropdown = Helper::getColNamesByControl($props, 'dropdown');
        $instance = new $modelPath;
        $eloquentParams = array_values($instance->eloquentParams);
        $_colNames =  array_column($eloquentParams, 2);
        $modelPaths_colNames =  (array_column($eloquentParams, 2, 1));
        $colNames_modelPaths =  (array_column($eloquentParams, 1, 2));


        $dataListenTrigger = $this->triggerDataModel($modelPaths_colNames, $_colNames, $colName); // k
        $colNames_ModelNames = $this->indexColNamesAndModels($colNames_modelPaths, $colNamesHaveDropdown); //k2

        $triggers_colNames = array_column($listenersJson, 'triggers', 'column_name');

        $byFilter = Helper::filterConditionsInRel($type, $colName);

        return view('components.controls.dropdown')->with(compact('byFilter', 'triggers_colNames', 'colNames_ModelNames', 'colName', 'action', 'label', 'currentEntity', 'dataListenTrigger', 'listenersJson'));
    }


    public function triggerDataModel($modelPaths_colNames, $_colNames, $colName)
    {
        $result = [];
        foreach (array_keys($modelPaths_colNames) as $modelPath) {
            if (in_array($colName, $_colNames)) {
                $entityName = Str::getEntityNameFromModelPath($modelPath);
                $result[$entityName] = Helper::getDataFromPathModel($modelPath)->toArray();
            }
        };
        return $result;
    }

    public function indexColNamesAndModels($colNames_modelPaths, $colNamesHaveDropdown)
    {
        $result = [];
        foreach ($colNames_modelPaths as $colName => $modelPath) {
            if (in_array($colName, $colNamesHaveDropdown)) {
                $entityName = Str::getEntityNameFromModelPath($modelPath);
                $result[$colName] = $entityName;
            }
        };
        return $result;
    }
}
