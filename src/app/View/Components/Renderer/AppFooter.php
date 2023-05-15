<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Utils\AccessLoggerController;
use Illuminate\View\Component;

class AppFooter extends Component
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
        (new AccessLoggerController())();

        return view('components.renderer.app-footer');
    }
}
