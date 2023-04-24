<?php

namespace App\View\Components\Controls\InspChklst;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class CheckPointCreateNcrOnHold extends Component
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
        private $debug,
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
        $params = [
            'parent_type' => Str::modelPathFrom($this->line->getTable()),
            'parent_id' => $this->line->id,
            'description' => "During " . $this->line->description . ", ",
        ];
        if ($this->line->getProject) $params['project_id'] = $this->line->getProject->id;
        if ($this->line->getSubProject) $params['sub_project_id'] = $this->line->getSubProject->id;
        if ($this->line->getProdRouting) $params['prod_routing_id'] = $this->line->getProdRouting->id;
        if ($this->line->getProdOrder) $params['prod_order_id'] = $this->line->getProdOrder->id;
        // 'prod_discipline_id' => '',

        $href = route('qaqc_ncrs.create', $params);
        return view('components.controls.insp-chklst.check-point-create-ncr-on-hold', [
            'line' => $this->line,
            'href' => $href,
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'relatedNcrs' => $this->line->getNcrs,
        ]);
    }
}
