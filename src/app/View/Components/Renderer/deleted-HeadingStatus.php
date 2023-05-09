<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\View\Component;

class HeadingStatus extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type
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
            $libStatuses = LibStatuses::getFor($this->type);
            $slot = $data['slot'];
            $statusProps = $libStatuses[(string)$slot] ?? null;
            if (!$statusProps) {
                return '';
            }
            $color = $statusProps['color'];
            $colorIndex = $statusProps['color_index'];
            $title = $statusProps['title'];
            // return "<div class='h-2 mt-2.5 ml-2'><x-renderer.status>$slot</x-renderer.status></div>";
            $textColorIndex = 1000 - $colorIndex;
            $class = "text-{$color}-{$textColorIndex} bg-{$color}-{$colorIndex} rounded font-medium text-black text-xs px-2 py-1.5 leading-tight mx-1";
            return "<div class='h-2 mt-2.5 ml-2'><span class='$class'>$title</span></div>";
        };
    }
}
