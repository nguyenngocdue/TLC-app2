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
        $viewName = 'components.renderer.view-all-matrix-filter.' . $this->type;
        if (view()->exists($viewName)) {
            return Blade::render('<x-renderer.view-all-matrix-filter.' . $this->type . ' :type="$type" :dataSource="$dataSource" :viewportParams="$viewportParams"/>', $params);
        } else {
            return "Unknown type $this->type in type matrix filter (ViewAllTypeMatrixFilter)";
        }
        // switch ($this->type) {
        //     case 'hr_timesheet_workers':
        //         return Blade::render('<x-renderer.view-all-matrix-filter.hr_timesheet_workers :type="$type" :dataSource="$dataSource" :viewportParams="$viewportParams"/>', $params);
        //     case 'qaqc_wirs':
        //         return Blade::render('<x-renderer.view-all-matrix-filter.qaqc_wirs :type="$type" :dataSource="$dataSource" :viewportParams="$viewportParams"/>', $params);
        //     case 'hr_training_lines':
        //         return Blade::render('<x-renderer.view-all-matrix-filter.hr_training_lines :type="$type" :dataSource="$dataSource" :viewportParams="$viewportParams"/>', $params);

        //     default:
        //         return "Unknown type $this->type in type matrix filter (ViewAllTypeMatrixFilter)";
        // }
    }
}
