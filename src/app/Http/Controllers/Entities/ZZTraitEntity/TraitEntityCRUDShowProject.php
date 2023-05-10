<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Project;

trait TraitEntityCRUDShowProject
{
	public function showProject($id)
	{
		$project = Project::find($id);
		return view('dashboards.pages.entity-show-project', [
			'projectId' => $id,
			'project' => $project,
		]);
	}
}
