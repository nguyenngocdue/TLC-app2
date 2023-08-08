<?php

namespace App\View\Components\Social;

use Illuminate\View\Component;

class MenuItemList extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $title = false, private $dataSource = [])
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
        return view('components.social.menu-item-list',[
            'title' => $this->title,
            'dataSource' => $this->dataSource,
        ]);
    }
}
