<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Action extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $dataLine)
    {
        //
        // dd($this->dataLine);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $id = $this->dataLine['id'];
        return view('components.renderer.action', [
            'id' => $id,
        ]);
    }
}
