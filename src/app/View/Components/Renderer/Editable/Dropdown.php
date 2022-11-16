<?php

namespace App\View\Components\Renderer\Editable;

use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class Dropdown extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $name = "", private $cbbDataSource = [])
    {
        //
        // dump($dataSource);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.renderer.editable.dropdown', [
            'name' => $this->name,
            'cbbDataSource' => $this->cbbDataSource,
        ]);
    }
}
