<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Props;
use Illuminate\Support\Str;

trait TraitEntityCRUDCreateEdit
{
	public function create()
	{
		$action = CurrentRoute::getControllerAction();
		$props = Props::getAllOf($this->type);

		$type = $this->type;
		$modelPath = $this->data;
		$values = "";
		$idItems = [];
		return view('dashboards.pages.entity-create-edit')->with(compact('props', 'type', 'action', 'modelPath', 'values', 'idItems'));
	}

	public function edit($id)
	{
		$currentElement = $this->data::find($id);
		$props = Props::getAllOf($this->type);
		$type = Str::plural($this->type);
		$action = CurrentRoute::getControllerAction();
		$values = $action === "create" ? "" : $currentElement;

		$modelPath = $this->data;

		$idItems = $this->getManyToManyRelationship($currentElement);
		return view('dashboards.pages.entity-create-edit')->with(compact('props', 'values', 'type', 'action', 'currentElement', 'modelPath', 'idItems'));
	}
}
