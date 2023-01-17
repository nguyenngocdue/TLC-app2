<?php

namespace App\Http\Controllers\Entities;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Props;
use Illuminate\Support\Str;

trait TraitEntityCRUDCreateEdit
{
	public function create()
	{
		$action = CurrentRoute::getControllerAction();
		$props = Props::getAllOf($this->type);

		if ($props  === false) {
			$title = "Setting is missing";
			$type = 'warning';
			$message =  "File \"$this->r_fileName\" is missing. <br/>Please create this file by \"manage prop\".";
			return view('components.feedback.result')->with(compact('message', 'title', 'type'));
		}

		$type = $this->type;
		$modelPath = $this->data;
		$values = "";
		$idItems = [];
		return view('dashboards.pages.createEdit')->with(compact('props', 'type', 'action', 'modelPath', 'values', 'idItems'));
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
		// dd(123);
		return view('dashboards.pages.createEdit')->with(compact('props', 'values', 'type', 'action', 'currentElement', 'modelPath', 'idItems'));
	}
	/*
	private function _validate($props, Request $request)
	{
		$itemValidations = [];
		foreach ($props as $value) {
			if (!is_null($value['validation'])) $itemValidations[$value['column_name']] = $value['validation'];
		}
		$request->validate($itemValidations);
	}

	private function handleToggle($method, $props, &$dataInput)
	{
		$toggleControls = array_filter($props, fn ($prop) => $prop['control'] === 'toggle');
		$toggleControls = array_values(array_map(fn ($item) => $item['column_name'], $toggleControls));
		// dd($toggleControls);
		foreach ($toggleControls as $toggle) {
			$dataInput[$toggle] = isset($dataInput[$toggle]) ? ($method === 'store' ? $dataInput[$toggle] : 1)  : null;
		}
		return $dataInput;
	}

	private function handleTextArea($props, $newDataInput)
	{
		$colNameHasTextarea  = Helper::getColNamesByControlAndColumnType($props, 'textarea', 'json');
		if (count($colNameHasTextarea) > 0) $newDataInput = $this->modifyValueTextArea($colNameHasTextarea, $newDataInput);
		return $newDataInput;
	}

	private function modifyValueTextArea($colNameHasTextarea, $newDataInput)
	{
		$newTextAreas = [];
		foreach ($colNameHasTextarea as $_colName) {
			$object = $newDataInput[$_colName];
			$newTextAreas[$_colName] = json_decode(preg_replace("/\r|\n/", "", $object));
		}
		return array_replace($newDataInput, $newTextAreas);
	}
	*/
}
