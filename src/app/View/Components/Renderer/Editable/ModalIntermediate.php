<?php

namespace App\View\Components\Renderer\Editable;

use Illuminate\View\Component;

class ModalIntermediate extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $key,
        private $action,
        private $type,
        private $status,
        private $id,
        private $modelPath,
        private $actionButtons,
        private $props,
        private $item,
        private $dataSource,
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
        return view('components.renderer.editable.modal-intermediate', [
            'key' => $this->key,
            'action' => $this->action,
            'type' => $this->type,
            'status' => $this->status,
            'id' => $this->id,
            'modelPath' => $this->modelPath,
            'actionButtons' => $this->actionButtons,
            'props' => $this->props,
            'item' => $this->item,
            'dataSource' => $this->dataSource,
        ]);
    }
}
