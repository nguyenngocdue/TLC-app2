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
        private $lineId,
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
        return view(
            'components.controls.check-point-option',
            ['lineId' => $this->lineId]
        );
    }
}
