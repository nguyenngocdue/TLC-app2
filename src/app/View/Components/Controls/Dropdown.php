<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Models\User;
use App\Models\Zunit_test_9;
use App\Utils\Support\Entities;
use App\Utils\Support\Listeners;
use App\Utils\Support\Props;
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



        $dataListenTrigger = [];
        $dataListenTrigger += $this->triggerDataModel($colNames_modelPaths, $colNamesHaveDropdown, $type); // k
        $colNames_ModelNames = $this->indexColNamesAndModels($colNames_modelPaths, $colNamesHaveDropdown); //k2

        return view('components.controls.dropdown')->with(compact('colNames_ModelNames', 'colName', 'action', 'label', 'currentEntity', 'dataListenTrigger', 'listenersJson'));
    }



    public function triggerDataModel($colNames_modelPaths, $colNamesHaveDropdown, $type)
    {
        $result = [];
        foreach ($colNames_modelPaths as $colNameKey => $modelPath) {
            if (in_array($colNameKey, $colNamesHaveDropdown)) {
                $entityName = Str::getEntityNameFromModelPath($modelPath);
                $result[$entityName][$colNameKey] = Helper::getDataFromPathModelDropdown($modelPath, $colNameKey, $type)->toArray();
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
