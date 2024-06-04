<?php

namespace App\View\Components\Print;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityListenDataSource;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitGetOptionPrint;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitSupportEntityCRUDCreateEdit2;
use App\Utils\ClassList;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\DefaultValues;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\WorkflowFields;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class PrintProps extends Component
{
	use TraitEntityListenDataSource;
	use TraitSupportEntityCRUDCreateEdit2;
	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct(
		private $type,
		private $id,
		private $trashed,
		private $modelPath,
		private $layout,
		private $numberOfEmptyLines = 0,
		private $printMode = 'normal',
		private $topTitle = "",
		private $item = null,
	) {
		//
	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{
		$blackList = ['z_divider', 'z_page_break'];
		$superProps = SuperProps::getFor($this->type);
		$props = $superProps['props'];
		if (isset($props['_doc_id'])) $propsDocId = $props['_doc_id'];
		// $hiddenPrintMode = $this->printMode == 'template' ? 'hidden_template_print' : 'hidden_print';
		$hiddenPrintMode = 'hidden_print';
		$props = array_filter($props, fn ($item) => ($item[$hiddenPrintMode] ?? false) != true);
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
		$dataModelCurrent = $this->trashed ? ($this->modelPath)::onlyTrashed()->findOrFail($this->id) : ($this->modelPath)::findOrFail($this->id);
		$propsTemp = $props;
		if (isset($propsDocId) && !isset($propsTemp['_doc_id'])) $propsTemp['_doc_id'] = $propsDocId;
		foreach ($propsTemp as $key => $prop) {
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
		// $this->handleDataSourceOfControlSignatureMulti($propsTemp,$dataSource);
		$values = (object) $this->loadValueOfOracyPropsAndAttachments($dataModelCurrent, $props);
		$status = $dataSource['status'] ?? null;
		[$actionButtons, $buttonSave, $propsIntermediate] = $this->getConfigActionButtons($superProps, $status);
		$tableBluePrint = $this->makeTableBluePrint($props);
		$tableToLoadDataSource = [...array_values($tableBluePrint), $this->type];
		$typePlural = Str::plural($this->type);
		$propsTree = $this->formatPropTree(array_values($node));
		$params =  [
			'propsTree' => $propsTree,
			'dataSource' => $dataSource,
			'showId' => $this->id,
			'typePlural' => $typePlural,
			'type' => $this->type,
			'modelPath' => $this->modelPath,

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
			'routeUpdate' => route($typePlural . '.update', $this->id) ?? '',
			'layout' => $this->layout,
			'numberOfEmptyLines' => $this->numberOfEmptyLines,
			'printMode' => $this->printMode,
			'topTitle' => $this->topTitle,
			'item' => $this->item,
		];
		return view('components.print.print-props', $params);
	}
	private function handleDataSourceOfControlSignatureMulti($props, &$dataSource)
	{
		$propSignatureMulti = array_filter($props, function ($value) {
			return $value['control'] == 'signature_multi';
		});
		// dd($propSignatureMulti);
		foreach ($propSignatureMulti as $key => $value) {
			$tmp = substr($key, 1);
			$dataSource[$tmp] = [
				"signature_multi" => $dataSource[$tmp],
				"parent" => $dataSource[$tmp . "_list"]
			];
		}
	}
	private function getConfigActionButtons($superProps, $status)
	{
		$tmp = WorkflowFields::resolveSuperProps($superProps, $status, $this->type, true, CurrentUser::id());
		[,,, $actionButtons,, $buttonSave, $propsIntermediate] = $tmp;
		return [$actionButtons, $buttonSave, $propsIntermediate];
	}
	private function formatPropTree($propTree)
	{
		foreach ($propTree as &$value) {
			if (isset($value['children'])) {
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
							if ($key == $keyLast) {
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
