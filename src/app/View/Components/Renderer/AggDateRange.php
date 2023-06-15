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
        private $renderRaw = false,
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
            if (!empty($json)) {
                $dates = array_map(fn ($item) => $item->{$rendererParam}, $json);
                $min = DateTimeConcern::convertForLoading('picker_date', min($dates));
                $max  = DateTimeConcern::convertForLoading('picker_date', max($dates));
                $result = ($min == $max) ? $min : "$min to $max";
                if ($this->renderRaw) return $result;
                return "<p class='p-2'>$result</p>";
            }
        };
    }
}
