<?php

namespace App\View\Components\Controls;

use App\Http\Services\ReadingFileService;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Dropdown extends Component
{
    public function __construct(private $id, private ReadingFileService $readingFileService, private $colName, private $type, private $tablePath, private $action)
    {
    }

    public function render()
    {
        $colName = $this->colName;
        $u = new $this->tablePath();
        $eloquentParam = $u->eloquentParams;
        $selected = $this->id;

        $keyNameEloquent = "";
        foreach ($eloquentParam as $key => $value) {
            if (isset(array_flip($value)[$colName])) {
                $keyNameEloquent = $key;
                break;
            }
        }
        // dd($this->tablePath, $eloquentParam, $tableName, $colName);

        if ($keyNameEloquent === "") {
            $error =  "Not found ColumnName \"" . $colName . "\" in eloquentParams (in Model).";
            return view('components.render.alert')->with(compact('error'));
        }
        $pathSourceTable = $eloquentParam[$keyNameEloquent][1]; // filter name of path source Workplace table
        $insTable = new $pathSourceTable;
        $tableName = $insTable->getTable();

        $_dataSource = DB::table($tableName)->orderBy('name')->get();
        $dataSource = json_decode($_dataSource);


        $entityTable = $this->tablePath;
        $currentEntity = is_null($entityTable::find($this->id)) ? "" : $entityTable::find($this->id)->getAttributes();
        $action = $this->action;

        return view('components.controls.dropdown')->with(compact('dataSource', 'selected', 'colName', 'currentEntity', 'tableName', 'action'));
    }
}
