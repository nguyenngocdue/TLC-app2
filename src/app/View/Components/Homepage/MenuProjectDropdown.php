<?php

namespace App\View\Components\Homepage;

use App\Models\Project;
use App\Scopes\AccessibleProjectScope;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Log;
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
            ->whereIn('status', config("project.active_statuses.projects"))
            ->with('getAvatar')
            ->orderBy('name')
            ->get();

        $this->itemAllProject = Project::query()
            ->withoutGlobalScope(AccessibleProjectScope::class)
            ->where('id', $this->ALL_PROJECTS_ID)
            ->with('getAvatar')
            ->get();

        $this->allProjects = $this->itemAllProject->merge($this->allProjects);

        foreach ($this->allProjects as &$project) {
            $path = ($project->getAvatar ? app()->pathMinio() . $project->getAvatar->url_thumbnail : '/images/modules.png');
            $project['src'] =  $path;
        }

        $this->itemAllProject = $this->allProjects->where('id', $this->ALL_PROJECTS_ID)->first();
    }

    private function getSelectedProject()
    {
        $selectedProjectId = $this->cu->settings["global"]["selected-project-id"] ?? null;
        if ($selectedProjectId) {
            $tmp = $this->allProjects->where('id', $selectedProjectId)->first();
            if ($tmp) return $tmp;
        }
        return $this->itemAllProject;
    }

    public function render()
    {
        return view('components.homepage.menu-project-dropdown', [
            'projects' => $this->allProjects,
            'selectedProject' => $this->getSelectedProject(),
            'route' => route('updateUserSettings'),
        ]);
    }
}
