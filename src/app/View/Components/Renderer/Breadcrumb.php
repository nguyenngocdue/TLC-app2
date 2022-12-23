<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Breadcrumb extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $type = false)
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
        switch ($this->type) {
            case 'icon':
                return view('components.renderer.breadcrumb-icon');
            case 'slash':
                return view('components.renderer.breadcrumb-slash');
            default:
                break;
        }
        return view('components.renderer.breadcrumb');
    }
}
