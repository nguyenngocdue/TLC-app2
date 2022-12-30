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

        $dataSource = Helper::getDataSource($modelPath, $colName, $type);
        $currentEntity = Helper::getItemModel($this->type, $this->id) ?? [];

        if (is_null($dataSource) || gettype($dataSource) === 'string') {
            $message =  "Not found ColumnName \"" . $colName . "\" in eloquentParams (in $modelPath Model).";
            return "<x-feedback.alert message='{$message}' type='warning' />";
        }

        $dataUsers = DB::table('users')->get()->toArray();
        $listenersJson = Listeners::getAllOf($type);
        $dataListenTo = [];
        foreach ($listenersJson as $value) {
            $listen_to = $value['listen_to'];
            $val = Helper::getDataSource($modelPath, $listen_to, $type);
            $dataListenTo[$listen_to] = array_values($val)[0];
        };
        return view('components.controls.dropdown')->with(compact('dataListenTo', 'dataSource', 'colName', 'action', 'label', 'currentEntity', 'dataUsers', 'listenersJson'));
    }
}
