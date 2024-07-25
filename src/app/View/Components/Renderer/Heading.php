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
        // private $xalign = 'left',
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
            // $font = "";
            // $size = 6 - $this->level;
            // $h = "h" . $this->level;
            // $labelExtra = $this->labelExtra;
            // $textSize = $size . "xl";
            // if ($size === 1) $textSize = "xl";
            // if ($size === 0) {
            //     $textSize = "base";
            // } else {
            //     $font = "font-medium";
            // }

            switch ($this->level) {
                case 1:
                    $text = "text-4xl text-4xl-vw";
                    break;
                case 2:
                    $text = "text-3xl text-3xl-vw";
                    break;
                case 3:
                    $text = "text-2xl text-2xl-vw";
                    break;
                case 4:
                    $text = "text-xl text-xl-vw";
                    break;
                case 5:
                    $text = "text-lg text-lg-vw";
                    break;
                default:
                case 6:
                    $text = "text-md text-md-vw";
                    break;
                case 7:
                    $text = "text-sm text-sm-vw";
                    break;
                case 8:
                    $text = "text-xs text-xs-vw";
                    break;
            }

            // $theClass = " leading-tight mx-4 dark:text-gray-300 $font text-{$textSize} text-{$this->xalign} $this->class";
            $theClass = $text . " leading-tight " . $this->class;
            $slot = $data['slot'];
            $slot = htmlspecialchars_decode($slot);

            $result = "<div class='$theClass' id='$id' title='$this->title' style='scroll-margin-top: {$this->scrollMarginTop}px;'>";
            $result .= $slot;
            $result .= "<p class='text-xs font-light italic1' >";
            $result .= $this->labelExtra;
            $result .= "</p>";
            $result .= "</div>";
            return  $result;
        };
        // return view('components.renderer.heading');
    }
}
