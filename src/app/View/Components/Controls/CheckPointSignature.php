<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class CheckPointSignature extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $table01Name,
        private $rowIndex,
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
        return view('components.controls.check-point-signature', [
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'line' => $this->line,
        ]);
    }
}
