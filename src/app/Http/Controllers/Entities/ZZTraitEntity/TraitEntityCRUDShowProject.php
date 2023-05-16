<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Workflow\LibApps;
use Illuminate\Support\Str;

trait TraitEntityCRUDShowProject
{
	public function showProject($id)
	{
		$modelPath = Str::modelPathFrom($this->type);
		$project = $modelPath::find($id);
		$appName = LibApps::getFor($this->type)['title'];
		return view('dashboards.pages.entity-show-project', [
			'projectId' => $id,
			'project' => $project,
			'table' => Str::plural($this->type),
			'appName' => $appName,
		]);
	}
}
