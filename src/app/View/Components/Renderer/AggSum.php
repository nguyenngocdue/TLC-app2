<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class AggSum extends Component
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
            $json = json_decode($data['slot']);
            if (!is_array($json)) $json = [$json];
            $column = $this->rendererParam;
            if ($column == '') return;
            $sum = 0;
            foreach ($json as $line) {
                if (!isset($line->$column)) {
                    return "<x-renderer.tag title='Column not found' color='red'>" . $column . "</x-renderer.tag>";
                } else {
                    $sum += $line->$column;
                }
            }
            $sum = round($sum, 2);
            $count = sizeof($json);
            $avg = round($sum / $count, 2);
            return "<div class='text-center'><x-renderer.tag title='Count: $count\nAVG: $avg'>$sum</x-renderer.tag></div>";
        };
    }
}
