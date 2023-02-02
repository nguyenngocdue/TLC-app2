<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\DefaultValues;
use App\Utils\Support\Json\Props;
use Illuminate\Support\Str;

trait TraitEntityCRUDCreateEdit
{
	public function create()
	{
		$action = CurrentRoute::getControllerAction();
		$props = Props::getAllOf($this->type);
		$defaultValues = DefaultValues::getAllOf($this->type);

		$type = $this->type;
		$modelPath = $this->data;
		$values = "";
		$idItems = [];
		$title = "Add New";
		$topTitle = CurrentRoute::getTitleOf($this->type);
		$listenerDataSource = [];
		$listeners = [];
		$filters = [];
		return view('dashboards.pages.entity-create-edit')->with(compact(
			'props',
			'defaultValues',
			'type',
			'action',
			'modelPath',
			'values',
			'idItems',
			'title',
			'topTitle',
			'listenerDataSource',
			'listeners',
			'filters',
		));
	}

	public function edit($id)
	{
		$currentElement = $this->data::find($id);
		$props = Props::getAllOf($this->type);
		$defaultValues = DefaultValues::getAllOf($this->type);
		$type = Str::plural($this->type);
		$action = CurrentRoute::getControllerAction();
		$values = $action === "create" ? "" : $currentElement;

		$modelPath = $this->data;

		$idItems = $this->getManyToManyRelationship($currentElement);
		$title = "Edit";
		$topTitle = CurrentRoute::getTitleOf($this->type);
		$listenerDataSource = [];
		$listeners = [];
		$filters = [];
		return view('dashboards.pages.entity-create-edit')->with(compact(
			'props',
			'defaultValues',
			'values',
			'type',
			'action',
			'currentElement',
			'modelPath',
			'idItems',
			'title',
			'topTitle',
			'listenerDataSource',
			'listeners',
			'filters',
		));
	}
}
