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
        private $id = '',
        private $level = 6,
        private $title = '',
        private $xalign = 'left',
        private $class = '',
        private $labelExtra = '',
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
            $id = $this->id;
            $font = "";
            $size = 6 - $this->level;
            $h = "h" . $this->level;
            $labelExtra = $this->labelExtra;
            $textSize = $size . "xl";
            if ($size === 1) $textSize = "xl";
            if ($size === 0) {
                $textSize = "base";
            } else {
                $font = "font-medium";
            }

            $theClass = " $font leading-tight text-{$textSize}  my-2 text-{$this->xalign} dark:text-gray-300 $this->class";
            $slot = $data['slot'];
            $slot = htmlspecialchars_decode($slot);
            return "<$h  class='$theClass' id='$id' title='$this->title' style='scroll-margin-top: 90px;'> $slot <p class='text-sm font-light italic' >$labelExtra</p></$h>";
        };
        // return view('components.renderer.heading');
    }
}
