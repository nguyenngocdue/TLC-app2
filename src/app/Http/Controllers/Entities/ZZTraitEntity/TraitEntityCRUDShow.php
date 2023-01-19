<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\Props;

trait TraitEntityCRUDShow
{
	public function show($id)
	{
		$action = CurrentRoute::getControllerAction();
		$props = Props::getAllOf($this->type);

		$modelPath = new $this->data;
		$data = [];

		$ins = new $modelPath();
		$eloquentParams = $ins->eloquentParams;
		$colName_keyEloquent = [];
		foreach ($eloquentParams as $key => $value) {
			$colName_keyEloquent[$value[2]] =  $key;
		}

		// getDataFromModel
		$dataSource = [];
		$dataOnModel = $modelPath::find($id);
		foreach ($props as $key => $prop) {
			$colName = $prop['column_name'];
			if (isset($colName_keyEloquent[$colName])) {
				$dataSource[$colName] = $dataOnModel->{$colName_keyEloquent[$colName]}->name;
			} else {
				if ($prop['column_type'] !== 'static') {
					$dataSource[$colName] = $dataOnModel->{$colName};
				}
			}
		}

		// definitions heading in props
		$valueProps =  array_values($props);
		$indexHeading = [];
		foreach ($valueProps as $key => $value) {
			if ($value['column_type'] === 'static') {
				$indexHeading[] = $key;
			}
		};
		// dd($indexHeading);
		$indexProps = [];
		if (!empty($indexHeading)) {
			$indexProps = array_slice($valueProps, 0, $indexHeading[0]);
			foreach ($indexHeading as $key => $val) {
				if ($key + 1 < count($indexHeading)) {
					$len = $indexHeading[$key + 1] - $indexHeading[$key];
					$indexProps[] = array_slice($valueProps, $val, $len);
					// dump(count($indexHeading));
				} else {
					$len = count($valueProps) -  $indexHeading[$key];
					$indexProps[] = array_slice($valueProps, $val, $len);
				}
			}
		} else {
			$indexProps = $valueProps;
		};

		// Arrangement data
		$props = [];
		foreach ($indexProps as $key => $prop) {
			if (isset($prop[0])) {
				$p = $prop[0];
				$array = [];
				$array['label'] = $p['label'];
				$array['column_name'] = $p['column_name'];
				$array['control'] = $p['control'];
				$array['col_span'] = $p['col_span'];
				$array['new_line'] = $p['new_line'];
				$array['children'] = array_slice($prop, 1, count($prop) - 1);
				$props[$p['column_name']] = $array;
			} else {
				$array = [];
				$array['label'] = "";
				$array['column_name'] = $prop['column_name'];
				$array['control'] = $prop['control'];
				$array['col_span'] = $prop['col_span'];
				$array['new_line'] = $prop['new_line'];
				$array['children'] = [$prop];
				$props[$prop['column_name']] = $array;
			}
		}
		// dd($props, $dataSource);
		// dd($props);

		return view('dashboards.pages.entity-show')->with(compact('props', 'dataSource'));
	}
}
