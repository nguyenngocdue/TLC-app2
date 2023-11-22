<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperWorkflows;
use Illuminate\View\Component;

class Workflow403Checker extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $allowed = false,
        private $status = '',
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->status === '') return;
        return view('components.controls.workflow403-checker', ['allowed' => $this->allowed,]);
    }
}
