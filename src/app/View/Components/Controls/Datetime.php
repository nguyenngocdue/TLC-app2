<?php

namespace App\View\Components\Controls;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Datetime extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    private $id;
    private $label;

    public function __construct($id, $label)
    {
        $this->id = $id;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $id = $this->id;
        $label = $this->label;
        $dataSource = DB::table('users')->select('id', 'created_at', 'updated_at')->get();
        return view('components.controls.datetime')->with(compact('dataSource', 'id', 'label'));
    }
}
