<?php

namespace App\View\Components\Controls;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class ControlStatus extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
        private $colName,
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
        $type = Str::ucfirst(Str::singular($this->type));
        $model = App::make("App\\Models\\$type");
        if (!method_exists($model, "transitionTo")) return "<x-feedback.alert type='warning' message='This model needs to use HasStatus trait.'></x-feedback.alert>";
        $cbb = $model->getAvailableStatuses();
        return view("components.controls.control-status", [
            'options' => $cbb,
            'colName' => $this->colName,
        ]);
    }
}
