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
        private $rendererParam = '',
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return function (array $data) {

            if ($this->rendererParam === '') return "renderer_param ?";
            $rendererParam = $this->rendererParam;

            $result = [];
            $json = json_decode($data['slot']);
            if (!is_array($json)) $json = [$json];
            foreach ($json as $item) {
                $id = $item->id ?? "";
                $value = null;
                if (!isset($item->$rendererParam)) {
                    // dump('l1'.$rendererParam);
                    if ($rendererParam !== 'name') {
                        $result[] = "Renderer View All Param [" . $rendererParam . "] is missing";
                        continue;
                    } else {
                        $value = "";
                        // $value = "Nameless #".($id); //<< This will cause eye noises
                    }
                } else {
                    $value = $item->$rendererParam;
                }
                $result[] = "<span title='#{$id}'>" . $value . "</span>";
            }
            echo "<p class='p-2'>" . join(", ", $result) . "</p>";
        };
    }
}
