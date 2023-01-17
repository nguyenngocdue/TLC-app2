<?php

namespace App\View\Components\Navigation;

use Illuminate\View\Component;

class Tabs extends Component
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
        $tabs = [
            [
                'label' => 'Tab 1',
                'key' => 'tab-1',
                'children' => "Content 01",
            ],
            [
                'label' => 'Tab 2',
                'key' => 'tab-2',
                'children' => "Content 02",
            ],
            [
                'label' => 'Tab 3',
                'key' => 'tab-3',
                'children' => "Content 03",
            ],
        ];
        return view('components.navigation.tabs', [
            'tabs' => $tabs,
            'defaultTabKey' => 'tab-1',
            'tabId' => md5(microtime(true)),
        ]);
    }
}
