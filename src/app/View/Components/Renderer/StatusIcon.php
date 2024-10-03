<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class StatusIcon extends Component
{
    public function __construct(
        private $href = null,
        private $title = null,
        private $tooltip = null,
        private $icon = null
    ) {
    }

    public function render()
    {
        return function ($data) {

            $icon = htmlspecialchars_decode($data['slot']);
            $title  = $this->title;
            $tooltip = $this->tooltip;
            $statuses = LibStatuses::getAll();
            $color = isset($statuses[$icon]['color']) ? $statuses[$icon]['color'] : 'violet';
            $colorIndex = isset($statuses[$icon]['color_index']) ? $statuses[$icon]['color_index'] : 200;
            $bgIndex = 1000 - $colorIndex;
            if (is_null($title)) {
                $title = isset($statuses[$icon]) ? $statuses[$icon]['title'] : ($icon ? Str::headline($icon) : "null");
            }
            if (is_null($tooltip)) {
                $tooltip = $title;
            }
            $class = "hover:bg-{$color}-{$bgIndex} hover:text-{$color}-{$colorIndex}";
            return view('components.renderer.status-icon', [
                'href' => $this->href,
                'title' => $title,
                'tooltip' => $tooltip,
                'color' => $color,
                'colorIndex' => $colorIndex,
                'class' => $class,
                'icon' => $icon,
            ]);
        };
    }
}
