<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait TraitEntityCRUDStoreUpdate2
{
	private function getProps1()
	{
		$result = [];
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
		// dump($result);
		return $result;
	}

	private function getValidationRules()
	{
		$rules = [];
		foreach ($this->superProps['props'] as $prop) $rules[$prop['column_name']] = $prop['default-values']['validation'];
		$rules = array_filter($rules, fn ($i) => $i);
		// dump($rules);
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
			if ($prop['control'] === 'textarea' && $prop['control_type'] === 'json') {
				$column_name = $prop['column_name'];
				$dataSource[$column_name] = json_decode(preg_replace("/\r|\n/", "", $dataSource[$column_name]));;
			}
		}
		return $dataSource;
	}

	private function handleFields(Request $request, $isStoring)
	{
		// $request->validate($this->getValidationRules());

		$dataSource = $request->except(['_token', '_method', 'created_at', 'updated_at']);
		if ($isStoring) $dataSource['id'] = null;
		$dataSource = $this->apply_formula($dataSource, $this->type);
		$dataSource = $this->handleToggle($dataSource);
		$dataSource = $this->handleTextArea($dataSource);
		// $dataSource = $this->handleStatus($dataSource);

		dump($dataSource);
	}

	public function store(Request $request)
	{
		dump($request);
		$props = $this->getProps1();
		$this->handleFields($request, $props, 1);
		dd("Storing ...");
	}

	public function update(Request $request, $id)
	{
		dump($request);
		$props = $this->getProps1();
		$this->handleFields($request, $props, 0);
		dd("Updating ...");
	}
}
