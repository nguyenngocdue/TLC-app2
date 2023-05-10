<?php

namespace App\View\Components\Renderer\Project;

use App\Http\Controllers\Workflow\LibApps;
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

    private function getColumns()
    {
        return [
            ['dataIndex' => "doc_type"],
            ['dataIndex' => "progress"],
            ['dataIndex' => "total_open"],
        ];
    }

    private function getDataSource()
    {
        $apps = LibApps::getAll();
        $apps = array_filter($apps, fn ($app) => $app['show_in_my_view'] ?? false);

        $result = [];
        foreach ($apps as $appKey => $app) {
            // dump($app);
            $item =   [
                'doc_type' => "<a class='text-blue-700 cursor-pointer' title='" . $app['title'] . "' href='{$app['href']}'>" . Str::upper($app['nickname']) . "</a>",
                'progress' => 'b',
                'total_open' => 'total_open',
            ];
            $result[] = $item;
        }

        return $result;
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
            'columns' => $this->getColumns(),
            'dataSource' => $this->getDataSource(),
        ]);
    }
}
