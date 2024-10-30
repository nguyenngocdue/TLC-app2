<?php

namespace App\View\Components\Modals;

use Illuminate\View\Component;

class ModalReportChart extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $modalId = null,
        private $params = null,
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
        // dd($this->params);
        return view('components.modals.modal-report-chart',
            [
                'modalId' => $this->modalId,
            ]);
    }
}


