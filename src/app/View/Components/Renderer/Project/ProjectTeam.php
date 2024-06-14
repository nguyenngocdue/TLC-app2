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
        private $function = 'getProjectMembers',
    ) {
        //
    }

    private function getColumns()
    {
        return [
            [
                'dataIndex' => 'avatar',
                'renderer' => 'thumbnail'
            ],
            ['dataIndex' => 'name',],
            [
                'dataIndex' => 'getUserCompany',
                'title' => 'Company',
                'renderer' => 'column',
                'rendererParam' => 'name',
            ],
            [
                'dataIndex' => 'position_rendered',
                'title' => 'Position',
            ],
            ['dataIndex' => 'phone', 'title' => 'Mobile'],
            ['dataIndex' => 'email'],
        ];
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
        $dataSource = $project->{$this->function};
        return view(
            'components.renderer.project.project-team',
            [
                'project' => $project,
                'columns' => $this->getColumns(),
                'dataSource' => $dataSource,
            ]
        );
    }
}
