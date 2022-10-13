<?php

namespace App\View\Components\Controls;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Radio extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    private $id;
    private $colName;
    private $tablePath;
    private $action;
    public function __construct($id, $colName, $tablePath, $action)
    {
        $this->id = $id;
        $this->colName = $colName;
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
            // $error =  "Not found " . $colName . " in eloquenParams of " . $u;
            // return view('components.render.error')->with(compact('error'));
        }


        $pathSourceTable = $eloquenParam[$tableName][1]; // filter name of path source Workplace table
        $tableName = ($pathSourceTable)::first()->getTable();
        $dataSource = DB::table($tableName)->select('id', 'name', 'description')->get();


        $selected = $this->id;
        $entityTable = $this->tablePath;
        $currentEntity = is_null($entityTable::find($this->id)) ? "" : $entityTable::find($this->id)->getAttributes();
        $action = $this->action;
        return view('components.controls.radio')->with(compact('dataSource', 'selected', 'colName', 'currentEntity', 'span', 'action'));
    }
}
