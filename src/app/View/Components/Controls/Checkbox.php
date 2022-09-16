<?php

namespace App\View\Components\Controls;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Checkbox extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    private $id;
    private $colName;
    public function __construct($id, $colName)
    {
        $this->id = $id;
        $this->colName = $colName;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $span = 3; //<< 12/6/4/3/2/1
        $relationship =  (object)[
            "_workplace" => [
                "column_name" => "getWorkplace",
                "eloquent" => "belongsTo",
                "param_1" => "App\\Models\\Workplace",
                "param_2" => "workplace",
                "param_3" => null,
                "param_4" => null,
                "param_5" => null,
                "param_6" => null,
                "label" => "Posts",
                "control" => "count",
                "col_span" => "12",
                "new_line" => "false",
                "type_line" => "default"
            ]
        ];

        $column_name = $relationship->{'_workplace'}['column_name'];
        $u = User::first()->eloquentParams;
        $pathTable = $u[$column_name][1];
        $table = ($pathTable)::first()->getTable();
        $dataSource = DB::table($table)->select('id', 'name', 'description')->get();
        $selected = $this->id;
        $colName = $this->colName;

        return view('components.controls.checkbox')->with(compact('dataSource', 'selected', 'colName', 'span'));
    }
}
