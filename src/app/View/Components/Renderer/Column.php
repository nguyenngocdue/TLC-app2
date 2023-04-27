<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Column extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $cell,
        private $rendererParam = '',
        private $name = '',
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // return $this->rendererParam;
        if ($this->rendererParam === '') return "renderer_param ?";
        // $rendererParam = $this->rendererParam;
        // $slot = ($this->cell);
        // $result = [];
        // //$fault = false;
        // $json = json_decode($slot);
        // if (!is_array($json)) $json = [$json];
        // foreach ($json as $item) {
        //     if (!isset($item->$rendererParam)) {
        //         //$fault = true;
        //         break;
        //     }
        //     $result[] = "<span title='#{$item->id}'>" . $item->$rendererParam . "</span>";
        // }
        // echo join(", ", $result);
        return view('components.renderer.column', [
            'rendererParam' => $this->rendererParam,
        ]);
    }
}
