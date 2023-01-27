<?php

namespace App\View\Components\Navigation;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class Tabs extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $tabs = [],
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
        if (is_null($this->tabs)) return "Tab Data is NULL";
        if (sizeof($this->tabs) <= 0) return "Tab Data is an empty array";

        foreach ($this->tabs as &$tab) $tab['key'] = Str::snake($tab['label']);

        $defaultTabKey = $this->tabs[0]['key'];
        return view('components.navigation.tabs', [
            'tabId' => md5(microtime(true)),
            'tabs' => $this->tabs,
            'defaultTabKey' => $defaultTabKey,
        ]);
    }
}
