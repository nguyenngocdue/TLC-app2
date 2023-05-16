<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class RestoreColumn extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return function (array $data) {
            $type = $data['attributes']['type'];
            $name = $data['attributes']['name'];
            $id = $id = $data["slot"];
            $urlRestore = route($type . '.restore', $id) ?? '';
            return "
            <script>obj$id={urlRestore : '$urlRestore', id: '$id'}</script>
            <div>
                <x-renderer.button size='xs' value='$name' type='success' onClick='actionRestored(obj$id)' ><i class='fa-light fa-trash-can-arrow-up'></i></x-renderer.button>
            </div>
            ";
        };
    }
}
