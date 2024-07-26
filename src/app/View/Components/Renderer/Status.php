<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Status extends Component
{
    public function __construct(
        private $href = null,
        private $title = null,
        private $tooltip = null,
    ) {
    }

    public function render()
    {
        return function ($data) {

            $status = htmlspecialchars_decode($data['slot']);
            $title  = $this->title;
            $tooltip = $this->tooltip;
            $statuses = LibStatuses::getAll();
            $color = isset($statuses[$status]['color']) ? $statuses[$status]['color'] : 'violet';
            $colorIndex = isset($statuses[$status]['color_index']) ? $statuses[$status]['color_index'] : 200;
            $bgIndex = 1000 - $colorIndex;
            if (is_null($title)) {
                $title = isset($statuses[$status]) ? $statuses[$status]['title'] : ($status ? Str::headline($status) : "null");
            }
            if (is_null($tooltip)) {
                $tooltip = $status;
            }
            $class = "hover:bg-{$color}-{$bgIndex} hover:text-{$color}-{$colorIndex}";
            return view('components.renderer.status', [
                'href' => $this->href,
                'title' => $title,
                'tooltip' => $tooltip,
                'color' => $color,
                'colorIndex' => $colorIndex,
                'class' => $class,
            ]);
        };
    }
}
