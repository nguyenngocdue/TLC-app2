<?php

namespace App\View\Components\Homepage;

use App\Models\Project;
use App\Utils\AccessLogger\EntityIdClickCount;
use Illuminate\View\Component;

class MenuProjectMfAndCs extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $data = (new EntityIdClickCount)('project');
        $entitiesIds = collect($data)->pluck('entity_id')->toArray();
        $allProjectMFandCS = Project::getAllProjectByCondition()->map(function ($item) {
            $href = route('projects.show', $item->id) ?? '';
            $item['href'] = $href;
            return $item;
        });
        [$recent, $project] = $allProjectMFandCS->partition(function ($item) use ($entitiesIds) {
            return array_search($item->id, $entitiesIds) !== false;
        });
        return view('components.homepage.menu-project-mf-and-cs', [
            'recent' => $recent,
            'projects' => $project,
        ]);
    }
}
