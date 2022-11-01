<?php

namespace App\View\Components\Controls;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Checkbox extends Component
{
    public function __construct(private $id, private  $colName, private  $idItems, private  $action)
    {
    }

    public function render()
    {
        $colName = $this->colName;
        $dataSource = DB::table('workplaces')->get();
        $idItems = $this->idItems;
        $action = $this->action;
        return view('components.controls.checkbox')->with(compact('dataSource', 'colName', 'idItems', 'action'));
    }
}
