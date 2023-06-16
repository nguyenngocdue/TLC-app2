<?php

namespace App\View\Components\Renderer;

use App\Models\User_team_ot;
use Illuminate\View\Component;

class ViewAllTypeMatrix extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // $yAxis = User_team_ot
        return view('components.renderer.view-all-type-matrix');
    }
}
