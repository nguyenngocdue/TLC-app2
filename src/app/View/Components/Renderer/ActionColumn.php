<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class ActionColumn extends Component
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
            $urlDuplicate = route($type . '_dp', $id) ?? '';
            $urlDestroy = route($type . '.destroy', $id) ?? '';
            return "
            <script>obj$id={urlDuplicate : '$urlDuplicate', urlDestroy:'$urlDestroy', id: '$id'}</script>
            <div>
                <x-renderer.button size='xs' value='$name' type='secondary' onClick='actionDuplicated(obj$id)' ><i class='fa fa-copy'></i></x-renderer.button>
                <x-renderer.button size='xs' value='$name' type='danger' onClick='actionDeleted(obj$id)' ><i class='fa fa-trash'></i></x-renderer.button>
            </div>
            ";
        };
    }
}
