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
        private $dataSource = [],
        private $id = '',
        private $activeTab = '',
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
        foreach ($this->dataSource as &$tab) {
            if (!isset($tab['class'])) $tab['class'] = "";
            if ($tab['active'] ?? false) {
                $tab['class'] .= " bg-white -mb-px";
            } else {
                $tab['class'] .= " bg-gray-200";
            }
        }
        return view('components.renderer.tab-pane', [
            'dataSource' => $this->dataSource,
            'id' => $this->id,
        ]);
    }
}
