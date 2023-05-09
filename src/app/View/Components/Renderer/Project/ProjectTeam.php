<?php

namespace App\View\Components\Renderer\Project;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class ProjectTeam extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $table = 'projects',
        private $id = 1,
        private $function = 'getTeamMembers',
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
        dump($project);
        $dataSource = $project->{$this->function}();
        dump($dataSource);
        return view('components.renderer.project.project-team');
    }
}
