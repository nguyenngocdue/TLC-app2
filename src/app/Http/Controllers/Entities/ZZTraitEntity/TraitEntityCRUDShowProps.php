<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\ClassList;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\DefaultValues;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\WorkflowFields;
use Illuminate\Support\Str;

trait TraitEntityCRUDShowProps
{
	use TraitGetOptionPrint;
	public function showProps($id, $trashed)
	{
		$blackList = ['z_divider', 'z_page_break'];
		$superProps =SuperProps::getFor($this->type);
		$props = $superProps['props'];
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
		$dataSource = [];
		$dataModelCurrent = $trashed ? ($this->data)::onlyTrashed()->findOrFail($id) : ($this->data)::findOrFail($id);
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
		$valueOptionPrint = $this->getValueOptionPrint();
		$values = (object) $this->loadValueOfOracyPropsAndAttachments($dataModelCurrent, $props);
		$status = $dataSource['status'] ?? null;
		[$actionButtons,$buttonSave,$propsIntermediate] = $this->getConfigActionButtons($superProps,$status);
		$tableBluePrint = $this->makeTableBluePrint($props);
		$tableToLoadDataSource = [...array_values($tableBluePrint), $this->type];
		$typePlural =Str::plural($this->type);
		$params =  [
			'propsTree' => $this->formatPropTree(array_values($node)),
			'dataSource' => $dataSource,
			'type' => $this->type,
			'typePlural' => $typePlural,
			'showId' => $id,
			'modelPath' => $this->data,
			'topTitle' => CurrentRoute::getTitleOf($this->type),
			'classListOptionPrint' => ClassList::DROPDOWN,
			'valueOptionPrint' => $valueOptionPrint,
			'layout' => $this->getLayoutPrint($valueOptionPrint),
			'actionButtons' => $actionButtons,
			'buttonSave' => $buttonSave,
			'propsIntermediate' => $propsIntermediate,
			'defaultValues' => DefaultValues::getAllOf($this->type),
			'values' => $values,
			'status' => $status,
			'item' => $dataModelCurrent,
			'listenerDataSource' => $this->renderListenDataSource($tableToLoadDataSource),
			'listeners2' => $this->getListeners2($this->type),
			'filters2' => $this->getFilters2($this->type),
			'listeners4' => $this->getListeners4($tableBluePrint),
			'filters4' => $this->getFilters4($tableBluePrint),
			'routeUpdate' => route($typePlural.'.update',$id) ?? '',
		];
		return view('dashboards.pages.entity-show-props', $params);
	}
	private function getConfigActionButtons($superProps,$status){
		$tmp = WorkflowFields::resolveSuperProps($superProps ,$status,$this->type,true,CurrentUser::id());
    	[, , , $actionButtons,, $buttonSave,$propsIntermediate] = $tmp;
		return [$actionButtons,$buttonSave,$propsIntermediate];
	}
	private function formatPropTree($propTree){
		foreach ($propTree as &$value) {
			if(isset($value['children'])){
				$previousColSpan = 0;
				$keyPrevious = null;
				$keyLast = array_key_last($value['children']);
				foreach ($value['children'] as $key => &$prop) {
					$colSpan = $prop['col_span'];
					$previousColSpan += $colSpan;
					switch ($previousColSpan) {
						case 12:
							$previousColSpan = 0;
							break;
						case 18:
							$value['children'][$keyPrevious]['col_span'] = "12"; 
							$previousColSpan = 0;
								break;
						case 6:
							if($key == $keyLast){
								$value['children'][$keyPrevious]['col_span'] = "12"; 
								$previousColSpan = 0;
							}
						default:
							# code...
							break;
					}
					$keyPrevious = $key;

				}
			}
		}
		return $propTree;
	}
}
