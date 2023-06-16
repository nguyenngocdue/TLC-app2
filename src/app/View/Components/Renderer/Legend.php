<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Legend extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource = null,
        private $title = null,
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
        $count = count($this->dataSource) ?? 0;
        $gridCss = $this->getGridCss($count);
        return view('components.renderer.legend', [
            'dataSource' => $this->dataSource,
            'title' => $this->title,
            'gridCss' => $gridCss,
        ]);
    }
    private function getGridCss($count)
    {
        $gridCss = '';
        switch ($count) {
            case 0:
            case 1:
                $gridCss = '';
                break;
            case 2:
                $gridCss = 'grid-cols-1 sm:grid-cols-2 gap-10';
                break;
            case 3:
                $gridCss = 'grid-cols-1 sm:grid-cols-2 gap-5';
                break;
            case 4:
                $gridCss = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5';
                break;
            case 5:
                $gridCss = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-5';
                break;
            case 6:
                $gridCss = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-5';
                break;
            case 7:
                $gridCss = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-5';
                break;
            case 8:
            case 9:
            case 10:
            case 11:
            case 12:
            default:
                $gridCss = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 2xl:grid-cols-8 gap-5';
                break;
        }
        return $gridCss;
    }
}
