<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class Sum extends Component
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
            $json = json_decode($data['slot']);
            if (!is_array($json)) $json = [$json];
            $sum = 0;
            foreach ($json as $line) {
                $sum += $line->total_hours;
            }
            return "<div class='text-center'><x-renderer.tag>$sum</x-renderer.tag></div>";
        };
    }
}
