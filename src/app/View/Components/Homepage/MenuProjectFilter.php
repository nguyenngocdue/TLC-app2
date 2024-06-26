<?php

namespace App\View\Components\Homepage;

use App\Models\Project;
use App\Utils\AccessLogger\EntityIdClickCount;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class MenuProjectFilter extends Component
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

        $cu = CurrentUser::get();
        $isAllowed = $cu->isAllowedDocType();
        if (!$isAllowed) return "";
        $data = (new EntityIdClickCount)('project');
        $entitiesIds = collect($data)->pluck('entity_id')->toArray();
        $projectList = collect([]);

        $selectedProjectId = $cu->settings["global"]["selected-project-id"] ?? null;

        // $permissions = CurrentUser::getPermissions(Auth::user());
        // if (in_array('read-projects', $permissions)) {
        if ($cu->isProjectClient()) {
            $statuses = config("project.active_statuses.projects");
            $allowedSubProjectIds = $cu->getAllowedSubProjectIds();
            // dump($allowedSubProjectIds);
            $projectList = Project::query();
            if ($allowedSubProjectIds) {
                $projectList = $projectList->whereHas('getSubProjects', fn ($q) => $q->whereIn('id', $allowedSubProjectIds));
            }
            $projectList = $projectList->whereIn('status', $statuses)->get();
            foreach ($projectList as $project) {
                $project->thumbnail_url = $project->getAvatarThumbnailUrl('/images/modules.png');
            }
        }

        $allProject = (object)[
            "id" => null,
            "name" => "All Projects",
            "description" => "",
            "thumbnail_url" => "/images/modules.png",
        ];
        $selectedProject = $allProject;
        if ($selectedProjectId) {
            $selectedProject = $projectList->find($selectedProjectId);
            // dump($selectedProject->name);
        }

        [$recent, $project] = $projectList->sortBy(function ($item) use ($entitiesIds) {
            return array_search($item->id, $entitiesIds);
        })->partition(function ($item) use ($entitiesIds) {
            return array_search($item->id, $entitiesIds) !== false;
        });

        return view('components.homepage.menu-project-filter', [
            'isRender' => count($projectList),
            'recent' => $recent,
            'projects' => $project,
            'route' => route('updateUserSettings'),
            'allProject' => $allProject,
            'selectedProject' => $selectedProject,
            'selectedProjectId' => $selectedProject->id,
        ]);
    }
}
