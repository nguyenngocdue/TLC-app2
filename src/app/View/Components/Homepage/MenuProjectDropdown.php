<?php

namespace App\View\Components\Homepage;

use App\Models\Project;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class MenuProjectDropdown extends Component
{
    private $allProjects;
    private $itemAllProject;
    private $cu;
    private $ALL_PROJECTS_ID = 81;

    public function __construct()
    {
        $this->cu = CurrentUser::get();

        $this->allProjects = Project::query()
            ->whereHas('getAccessibleUsers', function ($query) {
                $query->where('user_id', $this->cu->id);
            })
            ->orWhere('id', $this->ALL_PROJECTS_ID)
            ->with('getAvatar')
            ->orderByRaw('id = 81 DESC')
            ->get();

        foreach ($this->allProjects as &$project) {
            $path = ($project->getAvatar ? app()->pathMinio() . $project->getAvatar->url_thumbnail : '/images/modules.png');
            $project['src'] =  $path;
        }

        $this->itemAllProject = $this->allProjects->where('id', $this->ALL_PROJECTS_ID)->first();
    }

    private function getSelectedProject()
    {
        $selectedProjectId = $this->cu->settings["global"]["selected-project-id"] ?? null;
        if ($selectedProjectId) return $this->allProjects->where('id', $selectedProjectId)->first();
        return $this->itemAllProject;
    }

    public function render()
    {
        // $allProjects = $this->getAccessibleProjects();
        // dd($allProjects);
        return view('components.homepage.menu-project-dropdown', [
            'projects' => $this->allProjects,
            'selectedProject' => $this->getSelectedProject(),
            'route' => route('updateUserSettings'),
        ]);
    }
}
