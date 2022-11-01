<?php

namespace App\View\Components\Controls;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Dropdownmulti extends Component
{
    public function __construct(private $colName, private $idItems, private $action)
    {
    }

    public function render()
    {
        $span = 4; //<< 12/6/4/3/2/1
        $colName = $this->colName;
        $dataSource = DB::table('workplaces')->get();
        $idItems = $this->idItems;
        $action = $this->action;
        return view('components.controls.dropdownmulti')->with(compact('dataSource', 'colName', 'idItems', 'action', 'span'));
    }
}
