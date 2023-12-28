<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\GridCss;
use Illuminate\View\Component;

class Legend extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type = null,
        private $title = null,
        private $dataSource = null,
    ) {
        // dump($type);
    }

    private function getLegendDataSource()
    {
        return LibStatuses::getFor($this->type);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataSource = $this->dataSource ?  $this->dataSource : $this->getLegendDataSource();
        $count = count($dataSource);
        $gridCss = GridCss::getGridCss($count);
        return view('components.renderer.legend', [
            'dataSource' => $dataSource,
            'title' => $this->title,
            'gridCss' => $gridCss,
        ]);
    }
}
