<?php

namespace App\View\Components\Modals;

use App\Models\Hse_insp_tmpl_sht;
use Illuminate\View\Component;

class ModalClone extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $modalId,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataSource = Hse_insp_tmpl_sht::all();
        $url = route('cloneTemplateHse') ?? '';
        return view('components.modals.modal-clone',[
            'modalId' => $this->modalId,
            'dataSource' => $dataSource,
            'url' => $url,
        ]);
    }
}
