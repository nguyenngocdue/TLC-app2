<?php

namespace App\View\Components\Renderer\ViewAllMatrixFilterDataSource;

use App\Models\Project;

trait TraitFilterDataSourceCommon
{
    protected function getProjectListForFilter()
    {
        if (!$this->projectDatasource) {
            $subProjectDatasource = $this->getSubProjectListForFilter();
            $projectIds = [];
            foreach ($subProjectDatasource as $subProject) {
                $projectIds[] = $subProject->project_id;
            }
            $statuses = config("project.active_statuses.projects");
            $this->projectDatasource = Project::query()
                ->whereIn('id', $projectIds)
                ->whereIn('status', $statuses)
                ->get();
        } else {
            // echo "CACHE HIT - getProjectListForFilter";
        }
        return $this->projectDatasource;
    }
}
