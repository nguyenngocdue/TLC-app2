<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait TraitEntityCRUDStoreUpdate2
{
	private $debugForStoreUpdate = false;

	private function dump($title, $content)
	{
		if ($this->debugForStoreUpdate) {
			echo "$title";
			dump($content);
		}
	}

	private function getProps1()
	{
		$result = [
			'oracy_prop' => [],
			'eloquent_prop' => [],
		];
		foreach ($this->superProps['props'] as $prop) {
			switch ($prop['column_type']) {
				case 'oracy_prop':
				case 'eloquent_prop':
					$column_type = $prop['column_type'];
					break;
				default:
					$column_type = 'field';
					break;
			}
			$result[$column_type][] = $prop['name'];
		}
		$this->dump("getProps1", $result);
		return $result;
	}

	private function getValidationRules()
	{
		$rules = [];
		foreach ($this->superProps['props'] as $prop) {
			if (isset($prop['default-values']['validation'])) {
				$rules[$prop['column_name']] = $prop['default-values']['validation'];
			}
		}
		$rules = array_filter($rules, fn ($i) => $i);
		$this->dump("getValidationRules", $rules);
		return $rules;
	}

	private function handleToggle($dataSource)
	{
		foreach ($this->superProps['props'] as $prop) {
			if ($prop['control'] === 'toggle') {
				$column_name = $prop['column_name'];
				$dataSource[$column_name] = isset($dataSource[$column_name]);
			}
		}
		return $dataSource;
	}

	private function handleTextArea($dataSource)
	{
		foreach ($this->superProps['props'] as $prop) {
			if ($prop['control'] === 'textarea' && $prop['column_type'] === 'json') {
				$column_name = $prop['column_name'];
				$text = $dataSource[$column_name];
				$dataSource[$column_name] = /*json_decode*/ (preg_replace("/\r|\n/", "", $text));;
			}
		}
		return $dataSource;
	}

	private function handleStatus($theRow, $fields)
	{
		if (!isset($fields['status'])) return;
		if (!isset($theRow['status'])) return;
		$theRow->transitionTo($fields['status']);
	}

	private function handleCheckboxAndDropdownMulti(Request $request, $theRow, array $oracyProps)
	{
		foreach ($oracyProps as $prop) {
			$relatedModel = $this->superProps['props'][$prop]['relationships']['oracyParams'][1];
			$propName = substr($prop, 1); //Remove first "_"
			$ids = $request->input($propName);
			if (is_null($ids)) $ids = []; // Make sure it sync when unchecked all
			$ids = array_map(fn ($id) => $id * 1, $ids);

			$fieldName = substr($propName, 0, strlen($propName) - 2); //Remove parenthesis ()
			$theRow->syncCheck($fieldName, $relatedModel, $ids);
			$this->dump("handleCheckboxAndDropdownMulti $propName", $ids);
		}
	}

	private function handleFields(Request $request, $action)
	{
		$request->validate($this->getValidationRules());

		$dataSource = $request->except(['_token', '_method', 'status', 'created_at', 'updated_at']);
		if ($action === 'store') $dataSource['id'] = null;
		$dataSource = $this->applyFormula($dataSource);
		$dataSource = $this->handleToggle($dataSource);
		$dataSource = $this->handleTextArea($dataSource);

		$this->dump("handleFields", $dataSource);
		return $dataSource;
	}

	public function store(Request $request)
	{
		$this->dump("Request", $request->input());
		$props = $this->getProps1();
		$fields = $this->handleFields($request, __FUNCTION__);

		$theRow = $this->data::create($fields);

		$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
		$this->handleStatus($theRow, $fields);
		if ($this->debugForStoreUpdate) dd(__FUNCTION__ . " done");
		return redirect(route(Str::plural($this->type) . ".edit", $theRow->id));
	}

	public function update(Request $request, $id)
	{
		$this->dump("Request", $request->input());
		$props = $this->getProps1();
		$fields = $this->handleFields($request, __FUNCTION__);

		$theRow = $this->data::find($id);
		$theRow->fill($fields);
		$theRow->save();

		$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
		$this->handleStatus($theRow, $fields);
		if ($this->debugForStoreUpdate) dd(__FUNCTION__ . " done");
		return redirect(route(Str::plural($this->type) . ".edit", $theRow->id));
	}
}
