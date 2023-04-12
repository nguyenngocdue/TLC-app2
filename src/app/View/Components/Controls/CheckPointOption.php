<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class CheckPointOption extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $line,
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
        // dump($this->line->getControlGroup);
        $controlGroupNames = explode("|", $this->line->getControlGroup->name);
        // dump($controlGroupNames);

        return view(
            'components.controls.check-point-option',
            [
                'line' => $this->line,
                'controlGroupNames' => $controlGroupNames
            ]
        );
    }
}
