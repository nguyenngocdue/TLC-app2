<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
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
        private $id,
        private $action,
        private $modelPath,
    ) {
        //
        $model = ($this->modelPath)::find($this->id);
        $this->value = is_null($model) ? "new" : $model->status;
    }

    public function render()
    {
        $model = Helper::getItemModel($this->type);
        if (!method_exists($model, "transitionTo")) return "<x-feedback.alert type='warning' message='This model needs to use HasStatus trait.'></x-feedback.alert>";
        $cbb = $model->getAvailableStatuses();
        // $currentStatus = $this->action === 'edit' ? $model::find($this->id)->first()->status : '';
        return view("components.controls.control-status", [
            'options' => $cbb,
            'colName' => $this->colName,
            'value' => $this->value,
        ]);
    }
}
