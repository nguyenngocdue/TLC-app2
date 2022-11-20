<?php

namespace App\View\Components\Dashboards\pages;

use Illuminate\View\Component;

class Search extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $action = '')
    {
        //
        // dd($this->action);
        // dd("Searchbox");
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.search', [
            'action' => $this->action,
        ]);
    }
}
