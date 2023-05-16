<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Blade;
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
            $urlRestore = route($type . '.restoreMultiple') ?? '';
            $str = '<div>
                        <x-renderer.button size="xs" value="{{$name}}" type="success"  onClick="actionMultiple(\'{{$type}}\',\'{{$urlRestore}}\',\'restored\',\'{{$id}}\')"><i class="fa-light fa-trash-can-arrow-up"></i></x-renderer.button>
                    </div>';
            return Blade::render(
                $str,
                [
                    'name' => $name,
                    'type' => $type,
                    'urlRestore' => $urlRestore,
                    'id' => $id,
                ]
            );
        };
    }
}
