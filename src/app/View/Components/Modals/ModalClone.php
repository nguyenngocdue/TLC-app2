<?php

namespace App\View\Components\Modals;

use App\Models\Ghg_tmpl;
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
        private $cloneFor = '',
    ) {
        // dump($this->cloneFor);
    }

    private function getDataSource()
    {
        switch ($this->cloneFor) {
            case "ghg_sheets":
                return Ghg_tmpl::all();
            case "hse_insp_chklst_shts":
                return Hse_insp_tmpl_sht::all();
            default:
                return collect([]);
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        $dataSource = $this->getDataSource();
        $url = route('cloneTemplateHse') ?? '';
        return view('components.modals.modal-clone', [
            'modalId' => $this->modalId,
            'dataSource' => $dataSource,
            'url' => $url,
        ]);
    }
}
