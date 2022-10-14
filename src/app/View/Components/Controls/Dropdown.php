<?php

namespace App\View\Components\Controls;

use App\Http\Services\ReadingFileService;
use App\Models\User;
use App\Models\Zunit_test_1;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Dropdown extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

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
        $eloquenParam = $u->eloquentParams;

        $tableName = "";
        foreach ($eloquenParam as $key => $value) {
            if ($value[2] === $colName) {
                $tableName = $key;
                break;
            }
        }
        if ($tableName === "") {
            $error =  "Not found ColumnName:'" . $colName . "' in eloquenParams";
            return view('components.render.error')->with(compact('error'));
        }

        // dd($eloquenParam, $tableName, $colName);

        $pathSourceTable = $eloquenParam[$tableName][1]; // filter name of path source Workplace table

        $insTable = new $pathSourceTable;
        $tableName = $insTable->getTable();

        $dataSource = DB::table($tableName)->select('id', 'name', 'description')->get();
        $selected = $this->id;
        $entityTable = $this->tablePath;
        $currentEntity = is_null($entityTable::find($this->id)) ? "" : $entityTable::find($this->id)->getAttributes();
        $action = $this->action;
        return view('components.controls.dropdown')->with(compact('dataSource', 'selected', 'colName', 'currentEntity', 'tableName', 'action'));
    }
}
