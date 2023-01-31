<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\DefaultValues;
use App\Utils\Support\Json\Props;
use Illuminate\Support\Str;

trait TraitEntityCRUDCreateEdit2
{
	public function create()
	{
		$action = 'create';
		$props = $this->getProps();
		$defaultValues = DefaultValues::getAllOf($this->type);

		$type = $this->type;
		$modelPath = $this->data;
		$values = "";
		$title = "Add New";
		$topTitle = CurrentRoute::getTitleOf($this->type);
		return view('dashboards.pages.entity-create-edit')->with(compact('props', 'defaultValues', 'type', 'action', 'modelPath', 'values', 'title', 'topTitle'));
	}

	private function getProps()
	{
		$props = Props::getAllOf($this->type);
		$result = array_filter($props, fn ($prop) => $prop['hidden_edit'] !== 'true');
		return $result;
	}

	private function loadValueForCheckboxAndDropdownMulti($original, $props)
	{
		$values = $original->getOriginal();
		foreach ($props as $prop) {
			if ($prop['control'] === 'checkbox' || $prop['control'] === 'dropdown_multi') {
				// dump($prop);
				$name = $prop['column_name'];
				$field_name = substr($name, 0, strlen($name) - 2); //Remove parenthesis()
				$values[$name] = json_encode($original->getCheckedByField($field_name)->pluck('id')->toArray());
			}
		}
		return (object) $values;
	}

	public function edit($id)
	{
		$action = 'edit';
		$original = $this->data::find($id);
		$props = $this->getProps();

		$values = $this->loadValueForCheckboxAndDropdownMulti($original, $props);

		$defaultValues = DefaultValues::getAllOf($this->type);
		$type = Str::plural($this->type);

		$modelPath = $this->data;

		$title = "Edit";
		$topTitle = CurrentRoute::getTitleOf($this->type);
		return view('dashboards.pages.entity-create-edit')->with(compact('props', 'defaultValues', 'values', 'type', 'action', 'modelPath',  'title', 'topTitle'));
	}
}
