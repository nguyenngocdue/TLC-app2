<?php

namespace App\View\Components\Renderer\Project;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class ProjectOverview extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $table = 'projects',
        private $id = 1,
        private $function = 'getProjectMembers',
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
        $modelPath = Str::modelPathFrom($this->table);
        $project = $modelPath::find($this->id);
        return view('components.renderer.project.project-overview', [
            'project' => $project,
        ]);
    }
}
