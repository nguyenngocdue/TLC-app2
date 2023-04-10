<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\Props;
use App\Utils\Support\Json\SuperProps;

trait TraitEntityCRUDShow
{
	public function show($id)
	{
		$blackList = ['z_divider', 'z_page_break'];
		$props = SuperProps::getFor($this->type)['props'];
		$props = array_filter($props, fn ($item) => $item['hidden_print'] != true);
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
		$dataModelCurrent = $modelCurrent::findOrFail($id);

		foreach ($props as $key => $prop) {
			if ($prop['column_type'] !== 'static') {
				if (empty($prop['relationships'])) {
					$dataSource[$prop['column_name']] = $dataModelCurrent->{$prop['column_name']};
				} else {
					$relationships = $prop['relationships'];
					if ($relationships['relationship'] === 'belongsTo') {
						$dataSource[$prop['column_name']] = $dataModelCurrent
							->{$relationships['control_name_function']}->name ?? '';
					} else {
						isset($prop['relationships']['eloquentParams']) ?
							$dataSource[$prop['column_name']] = $dataModelCurrent
							->{$relationships['control_name_function']} :
							$dataSource[$prop['column_name']] = $dataModelCurrent
							->{$relationships['relationship']}(substr($prop['relationships']['control_name'], 0, -2));
					}
				}
			}
		}
		return view('dashboards.pages.entity-show', [
			'propsTree' => array_values($node),
			'dataSource' => $dataSource,
			'type' => $this->type,
			'showId' => $id,
			'modelPath' => $this->data,
			'topTitle' => CurrentRoute::getTitleOf($this->type),
		]);
	}
}
