<?php

namespace App\View\Components\Controls;

use App\Models\User;
use Illuminate\View\Component;

class uploadfile extends Component
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
        $id = $this->id;
        $colName = $this->colName;
        $user = User::where('id', $id)->first();
        return view('components.controls.uploadfile')->with(compact('id', 'colName'));
    }
}
