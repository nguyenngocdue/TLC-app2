<?php

namespace App\View\Components\Controls\InspChklst;

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
        private $debug = !false,
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
        $attachments = $this->line->insp_photos;

        return view('components.controls.insp-chklst.check-point', [
            'line' => $this->line,
            'controlType' => $controlType,
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'attachments' => $attachments,
            'debug' => $this->debug,
        ]);
    }
}
