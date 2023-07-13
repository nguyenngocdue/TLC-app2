<?php

namespace App\View\Components\Renderer\ViewAll;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class ViewAllTypeMatrixFilter extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
        private $dataSource,
        private $viewportParams,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $params = [
            "type" => $this->type,
            "dataSource" => $this->dataSource,
            "viewportParams" => $this->viewportParams,
        ];
        switch ($this->type) {
            case 'hr_timesheet_workers':
                return Blade::render('<x-renderer.view-all.view-all-type-matrix-filter-week-month :type="$type" :dataSource="$dataSource" :viewportParams="$viewportParams"/>', $params);
            case 'qaqc_wirs':
                return Blade::render('<x-renderer.view-all.view-all-type-matrix-filter-project-subproject :type="$type" :dataSource="$dataSource" :viewportParams="$viewportParams"/>', $params);
            case 'hr_training_lines':
                return Blade::render('<x-renderer.view-all.view-all-type-matrix-filter-training :type="$type" :dataSource="$dataSource" :viewportParams="$viewportParams"/>', $params);
            default:
                return "Unknown type $this->type in type matrix filter";
        }
    }
}
