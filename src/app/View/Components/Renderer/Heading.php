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
        private $scrollMarginTop = 90,
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

            $theClass = " leading-tight mx-4 dark:text-gray-300 $font text-{$textSize} text-{$this->xalign} $this->class";
            $slot = $data['slot'];
            $slot = htmlspecialchars_decode($slot);

            $result = "<$h  class='$theClass' id='$id' title='$this->title' style='scroll-margin-top: {$this->scrollMarginTop}px;'>";
            $result .= $slot;
            $result .= "<p class='text-xs font-light italic1' >";
            $result .= $labelExtra;
            $result .= "</p>";
            $result .= "</$h>";
            return  $result;
        };
        // return view('components.renderer.heading');
    }
}
