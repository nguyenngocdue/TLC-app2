<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\DefaultValues;
use App\Utils\Support\Json\Props;
use Illuminate\Support\Str;

trait TraitEntityCRUDCreateEdit2
{
	use TraitEntityListenDataSource;

	public function create()
	{
		$action = __FUNCTION__;
		$props = $this->getCreateEditProps();
		$defaultValues = DefaultValues::getAllOf($this->type);

		$type = $this->type;
		$modelPath = $this->data;
		$values = "";
		$title = "Add New";
		$topTitle = CurrentRoute::getTitleOf($this->type);
		$listenerDataSource = $this->renderListenDataSource();
		$listeners = $this->getListeners();
		$filters = $this->getFilters();
		return view('dashboards.pages.entity-create-edit')->with(compact(
			'props',
			'defaultValues',
			'type',
			'action',
			'modelPath',
			'values',
			'title',
			'topTitle',
			'listenerDataSource',
			'listeners',
			'filters',
		));
	}

	public function edit($id)
	{
		$action = __FUNCTION__;
		$original = $this->data::findOrFail($id);
		$props = $this->getCreateEditProps();

		$values = $this->loadValueOfOracyPropsAndAttachments($original, $props);

		$defaultValues = DefaultValues::getAllOf($this->type);
		$type = Str::plural($this->type);

		$modelPath = $this->data;

		$title = "Edit";
		$topTitle = CurrentRoute::getTitleOf($this->type);
		$listenerDataSource = $this->renderListenDataSource();
		$listeners = $this->getListeners();
		$filters = $this->getFilters();
		return view('dashboards.pages.entity-create-edit')->with(compact(
			'original',
			'props',
			'defaultValues',
			'values',
			'type',
			'action',
			'modelPath',
			'title',
			'topTitle',
			'listenerDataSource',
			'listeners',
			'filters',
		));
	}

	private function getCreateEditProps()
	{
		$props = Props::getAllOf($this->type);
		$result = array_filter($props, fn ($prop) => $prop['hidden_edit'] !== 'true');
		return $result;
	}

	private function loadValueOfOracyPropsAndAttachments($original, $props)
	{
		$values = $original->getOriginal();
		foreach ($props as $prop) {
			$name = $prop['column_name'];
			if ($prop['control'] === 'checkbox' || $prop['control'] === 'dropdown_multi') {
				$field_name = substr($name, 0, strlen($name) - 2); //Remove parenthesis()
				$values[$name] = json_encode($original->getCheckedByField($field_name)->pluck('id')->toArray());
			}
			if ($prop['control'] === 'attachment') {
				$values[$name] = $original->{$name};
			}
		}
		return (object) $values;
	}
}
