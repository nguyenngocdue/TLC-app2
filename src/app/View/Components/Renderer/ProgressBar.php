<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class ProgressBar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $cell = null,
        private $dataSource = null,
        private $modalId = null,
        private $height = '20px', // 20px
    ) {
        //
        // dump($this->dataSource);
        // dump($this->cell);
        if (!is_null($this->cell)) {
            $this->dataSource = $this->cell;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (is_null($this->dataSource)) return "<div class='p-2'>dataSource is null</div>";
        $dataSource = $this->dataSource;
        $classList = "shadow-none flex flex-col text-xs-vw text-center whitespace-nowrap justify-center";
        return view('components.renderer.progress-bar', [
            'dataSource' => $dataSource,
            'classList' => $classList,

            // 'renderAsButton' => true,
            'modalId' => $this->modalId,
            'height' => $this->height,
        ]);
    }
}
