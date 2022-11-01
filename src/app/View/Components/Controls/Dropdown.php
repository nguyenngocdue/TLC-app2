<?php

namespace App\View\Components\Controls;

use App\Http\Services\ReadingFileService;
use App\Utils\System\Timer;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;


class Dropdown extends Component
{

    private $id;
    private $colName;
    private $type;
    private $tablePath;
    private $action;
    public function __construct($id, ReadingFileService $readingFileService, $colName, $type, $tablePath, $action)
    {
        $this->id = $id;
        $this->readingFileService = $readingFileService;
        $this->colName = $colName;
        $this->type = $type;
        $this->tablePath = $tablePath;
        $this->action = $action;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        $colName = $this->colName;
        $u = new $this->tablePath();
        $eloquentParam = $u->eloquentParams;
        $selected = $this->id;


        $keyNameEloquent = "";
        foreach ($eloquentParam as $key => $value) {
            if ($value[2] === $colName) {
                $keyNameEloquent = $key;
                break;
            }
        }
        // dd($this->tablePath, $eloquentParam, $tableName, $colName);

        if ($keyNameEloquent === "") {
            $error =  "Not found ColumnName \"" . $colName . "\" in the Model (eloquentParams).";
            return view('components.render.alert')->with(compact('error'));
        }


        $pathSourceTable = $eloquentParam[$keyNameEloquent][1]; // filter name of path source Workplace table

        $insTable = new $pathSourceTable;
        $tableName = $insTable->getTable();

        $_dataSource = DB::table($tableName)->get();
        $dataSource = json_decode($_dataSource);
        // array_unshift($dataSource, (object)['id' => null, "name" => "Select your option", "description" => ""]);

        $entityTable = $this->tablePath;
        $currentEntity = is_null($entityTable::find($this->id)) ? "" : $entityTable::find($this->id)->getAttributes();

        $action = $this->action;

        return view('components.controls.dropdown')->with(compact('dataSource', 'selected', 'colName', 'currentEntity', 'tableName', 'action'));
    }
}
