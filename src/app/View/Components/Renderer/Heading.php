<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Heading extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $level = 6,
        private $title = '',
        private $align = 'left',
        private $class = '',
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
            $font = "";
            $size = 6 - $this->level;
            $h = "h" . $this->level;
            $textSize = $size . "xl";
            if ($size === 1) $textSize = "xl";
            if ($size === 0) {
                $textSize = "base";
            } else {
                $font = "font-medium";
            }

            $theClass = "{$this->class} $font leading-tight text-{$textSize} text-black my-2 text-{$this->align} dark:text-gray-300";
            $slot = $data['slot'];
            return "<$h class='$theClass' title='$this->title'>$slot</$h>";
        };
        // return view('components.renderer.heading');
    }
}
