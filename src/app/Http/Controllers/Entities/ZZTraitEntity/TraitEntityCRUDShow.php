<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\Props;
use App\Utils\Support\Json\SuperProps;

trait TraitEntityCRUDShow
{
	public function show($id)
	{
		$blackList = ['z_divider'];
		$props = SuperProps::getFor($this->type)['props'];
		$node = [];
		$nodeCount = 0;
		foreach ($props as $key => $value) {
			if ($value['column_type'] !== 'static') {
				if (empty($node)) {
					$node['node' . $nodeCount]['value'] = null;
					$node['node' . $nodeCount]['children'][$key] = $value;
				} else {
					$node['node' . $nodeCount]['children'][$key] = $value;
				}
			} else {
				$nodeCount++;
				if (in_array($value['control'], $blackList)) {
					$node['node' . $nodeCount]['value'] = $value;
					$node['node' . $nodeCount]['children'] = null;
				} else {
					$node['node' . $nodeCount]['value'] = $value;
				}
			}
		}
		$modelCurrent = new ($this->data);
		$dataSource = [];
		$dataModelCurrent = $modelCurrent::find($id);
		dump($props);
		foreach ($props as $key => $prop) {
			if ($prop['column_type'] !== 'static') {
				if (!empty($prop['relationships'])) {
					$dataSource[$prop['column_name']] = $dataModelCurrent->{$prop['column_name']};
				} else {
					$relationships = $props['relationships'];
					$prop['relationships']['eloquentParams'] ?
						$dataModelCurrent->$relationships['control_name_function']->name :
						$dataModelCurrent->$relationships['relationship_function']($dataModelCurrent->$props['relationships']['control_name']);
				}
			}
		}


		dd($dataSource);
		return view('dashboards.pages.entity-show', [
			'propsTree' => array_values($node),
			'dataSource' => $dataSource,
			'type' => $this->type,
			'topTitle' => CurrentRoute::getTitleOf($this->type),
		]);
	}
}
