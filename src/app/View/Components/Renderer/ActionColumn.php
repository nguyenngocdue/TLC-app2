<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Blade;
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
            $urlDuplicate = route($type . '_dp.duplicateMultiple') ?? '';
            $urlDestroy = route($type . '.destroyMultiple') ?? '';
            $str = '<div>
                        <x-renderer.button size="xs" value="{{$name}}" type="secondary" onClick="actionMultiple(\'{{$type}}\',\'{{$urlDuplicate}}\',\'duplicated\',\'{{$id}}\')" ><i class="fa fa-copy"></i></x-renderer.button>
                        <x-renderer.button size="xs" value="{{$name}}" type="danger"  onClick="actionMultiple(\'{{$type}}\',\'{{$urlDestroy}}\',\'deleted\',\'{{$id}}\')"><i class="fa fa-trash"></i></x-renderer.button>
                    </div>';
            return Blade::render(
                $str,
                [
                    'name' => $name,
                    'type' => $type,
                    'urlDuplicate' => $urlDuplicate,
                    'urlDestroy' => $urlDestroy,
                    'id' => $id,
                ]
            );
        };
    }
}
