<?php

namespace App\View\Components\Dashboards;

use Illuminate\View\Component;

class PerPage extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $type = '', private $action = '', private $pageLimit = '')
    {
        //
        // dd("In perpage");
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.per-page', [
            'type' => $this->type,
            'action' => $this->action,
            'pageLimit' => $this->pageLimit,
        ]);
    }
}
