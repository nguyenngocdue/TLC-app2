<?php

namespace App\View\Components\Renderer;

use App\Utils\Support\CurrentUser;
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
        private $status = null,
        private $action,
        private $type,
        private $modelPath,
        private $id = null,
        private $item = null,
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
        return view('components.renderer.item-render-props', [
            'dataSource' => $this->dataSource,
            'status' => $this->status,
            'action' => $this->action,
            'type' => $this->type,
            'modelPath' => $this->modelPath,
            'id' => $this->id,
            'item' => $this->item,
        ]);
    }
}
