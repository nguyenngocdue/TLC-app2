<?php

namespace App\View\Components\Controls;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Radio extends Component
{
    public function __construct(private $id, private $colName, private $tablePath, private $action)
    {
    }

    public function render()
    {
        $span = 6; //<< 12/6/4/3/2/1
        $colName = $this->colName;
        $colName = $this->colName;
        $u = new $this->tablePath();
        $eloquenParam = $u->eloquentParams;

        $tableName = [];
        foreach ($eloquenParam as $key => $value) {
            // dd($colName, $eloquenParam, $key, $value[2]);
            if ($value[2] === $colName) {
                $tableName = $key;
                break;
            }
        }
        if ($tableName === "") {
            $message =  "Not found ColumnName \"" . $colName . "\" in eloquentParams (in Model).";
            $type = "warning";
            return view('components.feedback.alert')->with(compact('message', 'type'));
        }
        $pathSourceTable = $eloquenParam[$tableName][1]; // filter name of path source Workplace table
        $insTable = new $pathSourceTable;
        $tableName = $insTable->getTable();
        $dataSource = DB::table($tableName)->get();
        $selected = $this->id;
        $entityTable = $this->tablePath;
        $currentEntity = is_null($entityTable::find($this->id)) ? "" : $entityTable::find($this->id)->getAttributes();
        $action = $this->action;
        return view('components.controls.radio')->with(compact('dataSource', 'tableName', 'selected', 'colName', 'currentEntity', 'span', 'action'));
    }
}
