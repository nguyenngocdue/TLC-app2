<?php

namespace App\View\Components\Controls;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Dropdown extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    private $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
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

        // $path = storage_path("/json/entities/user/props.json");
        // $props = json_decode(file_get_contents($path), true);
        // $relationship = $props["_workplace"];

        $column_name = $relationship->{'_workplace'}['column_name'];
        $u = User::first()->eloquentParams;
        $pathTable = $u[$column_name][1];
        $table = ($pathTable)::first()->getTable();
        $dataSource = DB::table($table)->select('id', 'name', 'description')->get();
        $selected = $this->id;
        return view('components.controls.dropdown')->with(compact('dataSource', 'selected'));
    }
}
