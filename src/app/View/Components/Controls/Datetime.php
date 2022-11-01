<?php

namespace App\View\Components\Controls;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Datetime extends Component
{
    public function __construct(private $id, private $label)
    {
    }

    public function render()
    {
        $id = $this->id;
        $label = $this->label;
        $dataSource = DB::table('users')->select('id', 'created_at', 'updated_at')->get();
        return view('components.controls.datetime')->with(compact('dataSource', 'id', 'label'));
    }
}
