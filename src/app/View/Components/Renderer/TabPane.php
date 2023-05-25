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
            if ($tab['active'] ?? false) {
                $tab['class'] .= " bg-white -mb-px";
            } else {
                $tab['class'] .= " bg-gray-200";
            }
        }
        return view('components.renderer.tab-pane', [
            'tabs' => $this->tabs,
            'id' => $this->id,
            'class' => $this->class,
        ]);
    }
}
