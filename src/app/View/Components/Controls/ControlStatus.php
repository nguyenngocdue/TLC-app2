<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class ControlStatus extends Component
{
    private $value;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
        private $colName,
        private $id,
        private $modelPath,
    ) {
        //
        $model = ($this->modelPath)::find($this->id);
        $this->value = is_null($model) ? "new" : $model->status;
    }

    public function render()
    {
        if (!method_exists($this->modelPath, "getAvailableStatuses")) return "<x-feedback.alert type='warning' message='This model needs to use HasStatus trait.'></x-feedback.alert>";
        $cbb = $this->modelPath::getAvailableStatuses();
        return view("components.controls.control-status", [
            'options' => $cbb,
            'colName' => $this->colName,
            'value' => $this->value,
        ]);
    }
}
