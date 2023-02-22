<?php

namespace App\View\Components\Renderer;

use App\Utils\Support\DateTimeConcern;
use Illuminate\View\Component;

class PickerDatetime4 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $cell = null,
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
        $this->cell = DateTimeConcern::convertForLoading('picker_datetime', $this->cell);
        return $this->cell;
    }
}
