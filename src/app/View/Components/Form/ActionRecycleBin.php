<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class ActionRecycleBin extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type = null,
        private $trashed = false,
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
        $trashed = $this->trashed;
        $type = $this->type;
        $route = $trashed ? route($type . '.index') : route($type . '.trashed');
        $nameButtonHref = $trashed ? "View All" : "Recycle Bin";
        $iconButtonHref = $trashed ? "<i class='fa-solid fa-table-cells'></i>" : "<i class='fa-solid fa-trash'></i>";
        $btnType = $trashed ?  "secondary" : "danger";
        return view('components.form.action-recycle-bin', [
            'route' => $route,
            'nameButtonHref' => $nameButtonHref,
            'iconButtonHref' => $iconButtonHref,
            'btnType' => $btnType,
        ]);
    }
}
