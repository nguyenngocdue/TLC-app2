<?php

namespace App\View\Components\Renderer\ViewAll;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class ViewAllTypeMatrix extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
        // private $viewportDate = null,
        // private $viewportMode = 'week',
    ) {
        // $this->viewportDate = strtotime($this->viewportDate ? $this->viewportDate : now());
        // if (!$this->viewportMode) $this->viewportMode = 'week';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        switch ($this->type) {
            case "hr_timesheet_workers":
                return Blade::render("<x-renderer.view-all.view-all-type-matrix-type-date-mode/>");
            case "qaqc_wirs":
                return Blade::render("<x-renderer.view-all.view-all-type-matrix-type-project-subproject-routing/>");
            case "hr_training_lines":
                return Blade::render("<x-renderer.view-all.view-all-type-matrix-type-training-course />");
            default:
                return "Unknown how to render matrix view for " . $this->type . " (ViewAllTypeMatrix).";
        }
    }
}
