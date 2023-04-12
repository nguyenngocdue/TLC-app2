<?php

namespace App\View\Components\Controls;

use App\Models\Control_type;
use Illuminate\View\Component;

class CheckPoint extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $line,
        private $table01Name,
        private $rowIndex,
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
        // dump($this->line);
        $controlType = Control_type::get()->pluck('name', 'id',);
        // dump($controlType);
        return view('components.controls.check-point', [
            'line' => $this->line,
            'controlType' => $controlType,
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
        ]);
    }
}
