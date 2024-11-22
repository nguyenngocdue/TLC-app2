<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class TabPane extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $tabs = [],
        private $id = '',
        private $activeTab = '',
        private $class = '',
        private $activeBgColor = 'gray',
        private $activeTextColor = 'gray',
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
        foreach ($this->tabs as &$tab) {
            if (!isset($tab['class'])) $tab['class'] = "";
            if (isset($tab['active']) && $tab['active']) {
                $tab['class'] .= " font-bold -mb-px bg-{$this->activeBgColor}-200 text-{$this->activeTextColor}-800";
            } else {
                $tab['class'] .= " bg-{$this->activeBgColor}-100 text-gray-700 ";
            }
        }
        return view('components.renderer.tab-pane', [
            'tabs' => $this->tabs,
            'id' => $this->id,
            'class' => $this->class,
            'activeBgColor' => $this->activeBgColor,
        ]);
    }
}
