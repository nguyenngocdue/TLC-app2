<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class ItemRenderProps extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource,
        private $action,
        private $type,
        private $modelPath,
        private $status = null,
        private $id = null,
        private $item = null,
        private $hasReadOnly = false,
        private $width = null,
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
        $params =  [
            'dataSource' => $this->dataSource,
            'status' => $this->status,
            'action' => $this->action,
            'type' => $this->type,
            'modelPath' => $this->modelPath,
            'id' => $this->id,
            'item' => $this->item,
            'hasReadOnly' => $this->hasReadOnly,
            'width' => $this->width,
        ];
        return view('components.renderer.item-render-props', $params);
    }
}
