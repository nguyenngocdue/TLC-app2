<?php

namespace App\View\Components\Print;

use Illuminate\View\Component;

class SettingLayoutReport extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $entity,
        private $class,
        private $value,
        private $typeReport,
        private $routeName,
        private $modeOption,
        
    ) {
        //setting-layout-report
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.print.setting-layout-report', [
            'entity' => $this->entity,
            'typeReport' => $this->typeReport,
            'class' => $this->class,
            'value' => $this->value,
            'route' => route($this->routeName),
            'modeOption' => $this->modeOption,
        ]);
    }
}
