<?php

namespace App\View\Components\Controls\InspChklst;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class CheckPointTextarea2 extends Component
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
        private $readOnly = false,
        private $rows = 4,
    ) {
        //
        // dump($readOnly);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view(
            'components.controls.insp-chklst.check-point-textarea2',
            [
                'line' => $this->line,
                'table01Name' => $this->table01Name,
                'rowIndex' => $this->rowIndex,
                'readOnly' => $this->readOnly,
                'rows' => $this->rows,
                'cuid' => CurrentUser::id(),
            ]
        );
    }
}
