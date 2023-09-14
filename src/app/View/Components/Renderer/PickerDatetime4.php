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
        // return "(predicated4)";
        $this->cell = str_starts_with($this->cell, "No dataIndex for ") ? 0 : $this->cell;
        if ($this->cell === 'DO_NOT_RENDER') return "";
        $this->cell = DateTimeConcern::convertForLoading('picker_datetime', $this->cell);
        return $this->cell;
    }
}
