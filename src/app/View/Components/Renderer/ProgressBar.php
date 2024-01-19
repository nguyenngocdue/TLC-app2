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
        // return "<div class='h-20'>
        // <button class=' (CLASSLIST-BUTTON) font-medium leading-tight rounded transition duration-150 ease-in-out focus:ring-0 focus:outline-n1one (END-OF-CLASSLIST) disabled:opacity-50 inline-blo1ck text-none border-2 border-gray-200 bg-gray-200 text-gray-800 hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg active:bg-gray-400 active:shadow-lg shadow-none flex flex-col1 text-xs item-center whitespace-nowrap text-white justify-center flex-col bg-green-500 h-full'>YES: 123</button>
        // </div>";
        if (is_null($this->dataSource)) return "<div class='p-2'>dataSource is null</div>";
        $dataSource = $this->dataSource;
        $classList = "shadow-none flex flex-col text-xs text-center whitespace-nowrap text-white justify-center";
        return view('components.renderer.progress-bar', [
            'dataSource' => $dataSource,
            'classList' => $classList,

            // 'renderAsButton' => true,
            'modalId' => $this->modalId,
            'height' => $this->height,
        ]);
    }
}
