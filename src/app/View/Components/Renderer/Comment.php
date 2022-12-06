<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Comment extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = '',
        private $type = '',
        private $id = '',
        private $readonly = '',
        private $colName = '',
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $ownerDB = Auth::user();
        $name = $this->name;
        $type = $this->type;
        $readonly = $this->readonly;
        return view('components.renderer.comment')->with(compact('name', 'type', 'readonly', 'ownerDB'));
    }
}
