<?php

namespace App\View\Components\Renderer;

use App\Utils\GridCss;
use App\Utils\Support\Json\SuperProps;
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

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $sp = SuperProps::getFor($this->type);
        $statuses = $sp['statuses'];
        // dump($statuses);
        $dataSource = $this->dataSource ?  $this->dataSource :  $statuses; //LibStatuses::getFor($this->type);
        $count = count($dataSource);
        $gridCss = GridCss::getGridCss($count);
        $params = [
            'dataSource' => $dataSource,
            'title' => $this->title,
            'gridCss' => $gridCss,
        ];
        // dump($params);
        return view('components.renderer.legend', $params);
    }
}
