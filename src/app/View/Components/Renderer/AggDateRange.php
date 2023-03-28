<?php

namespace App\View\Components\Renderer;

use App\Utils\Support\DateTimeConcern;
use Illuminate\View\Component;

class AggDateRange extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $rendererParam = '',
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
        return function (array $data) {
            $rendererParam = $this->rendererParam;
            $json = json_decode($data['slot']);
            // if (!is_array($json)) $json = [$json];
            $dates = array_map(fn ($item) => $item->{$rendererParam}, $json);
            // dump($dates);
            $min = DateTimeConcern::convertForLoading('picker_date', min($dates));
            $max  = DateTimeConcern::convertForLoading('picker_date', max($dates));
            $result = ($min == $max) ? $min : "$min to $max";
            return "<p class='p-2'>$result</p>";
        };
        // return view('components.renderer.agg-date-range');
    }
}
