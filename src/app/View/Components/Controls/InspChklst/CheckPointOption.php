<?php

namespace App\View\Components\Controls\InspChklst;

use App\Models\Qaqc_insp_control_value;
use Illuminate\View\Component;

class CheckPointOption extends Component
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
        $controlGroup = $this->line->getControlGroup;
        if ($controlGroup) {
            $options = Qaqc_insp_control_value::where('qaqc_insp_control_group_id', $controlGroup->id)->get();
            $options = $options->pluck('name', 'id',);
        } else {
            return "CONTROL GROUP ID IS NULL";
        }

        return view(
            'components.controls.insp-chklst.check-point-option',
            [
                'line' => $this->line,
                'options' => $options,
                'table01Name' => $this->table01Name,
                'rowIndex' => $this->rowIndex,
            ]
        );
    }
}
