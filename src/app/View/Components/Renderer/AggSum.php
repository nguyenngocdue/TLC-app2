<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class AggSum extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $rendererParam = '',
        private $rendererUnit = '',
        private $renderRaw = false,
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
            $unit = $this->rendererUnit;
            if ($column == '') return;
            $sum = 0;
            foreach ($json as $line) {
                if (!isset($line->$column)) {
                    if ($this->renderRaw) return $column;
                    else return "<div class='text-center'><x-renderer.tag title='Column not found' color='red'>" . $column . "</x-renderer.tag></div>";
                } else {
                    if (is_numeric($line->$column)) {
                        $sum += $line->$column;
                    } else {
                        return "Can't sum text. (e.g: {$line->$column})";
                    }
                }
            }
            $sum = round($sum, 2);
            $count = sizeof($json);
            if ($count === 0) return "";
            $avg = round($sum / $count, 2);
            $unit = Str::plural($unit, round($sum));
            $tooltip = "Count: $count\nAVG: $avg";
            if ($this->renderRaw) return "$sum $unit";
            return "<div class='text-center'><x-renderer.tag title='$tooltip'>$sum $unit</x-renderer.tag></div>";
        };
    }
}
