<?php

namespace App\View\Components\Controls;

use App\Models\User;
use App\Models\Workplace;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Checkbox extends Component
{

    private $id;
    private $colName;
    private $idsCheckbox;
    private $action;
    public function __construct($id, $colName, $idsCheckbox, $action)
    {
        $this->id = $id;
        $this->colName = $colName;
        $this->idsCheckbox = $idsCheckbox;
        $this->action = $action;
    }

    public function render()
    {

        $colName = $this->colName;
        $dataSource = DB::table('workplaces')->get();
        $idsCheckbox = $this->idsCheckbox;
        $action = $this->action;
        return view('components.controls.checkbox')->with(compact('dataSource', 'colName', 'idsCheckbox', 'action'));
    }
}
