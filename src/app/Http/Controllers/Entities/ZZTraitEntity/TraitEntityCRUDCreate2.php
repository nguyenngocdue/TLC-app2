<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Workflow\LibApps;
use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\DefaultValues;
use App\View\Components\Controls\DisallowedDirectCreationChecker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait TraitEntityCRUDCreate2
{
	use TraitEntityListenDataSource;
	use TraitSupportEntityCRUDCreateEdit2;
	use TraitSupportPermissionGate;

	protected function getEditTitle()
	{
		return "Edit";
	}

	protected function getEditTopTitle()
	{
		return CurrentRoute::getTitleOf($this->type);
	}

	public function create(Request $request)
	{
		if ($this->type == 'exam_sheet') {
			return Blade::render('<x-controls.exam-sheet.exam-sheet-page-create 
				type="{{$type}}"
			/>', [
				'type' => $this->type,
			]);
		}
		$superProps = $this->getSuperProps();
		$props = $superProps['props'];
		$defaultValues = $this->getDefaultValue($props);
		$values =  (object) array_merge($defaultValues, $request->input(), $this->loadValueOfOrphanAttachments($props));
		$tableBluePrint = $this->makeTableBluePrint($props);
		$tableToLoadDataSource = [...array_values($tableBluePrint), $this->type];
		$hasStatusColumn = Schema::hasColumn(Str::plural($this->type), 'status');
		$disallowed = DisallowedDirectCreationChecker::checkAgainstRequest($request, $this->type);
		if ($disallowed) {
			$creationLinks = DisallowedDirectCreationChecker::getCreationLinks($this->type);
			abort(403, "Please create via $creationLinks.");
		}
		return view('dashboards.pages.entity-create-edit', [
			'superProps' => $superProps,
			// 'props' => $props,
			'item' => (object)[],
			'defaultValues' => DefaultValues::getAllOf($this->type),
			'hasStatusColumn' => $hasStatusColumn,
			'type' => $this->type,
			'docId' => null,
			'action' => __FUNCTION__,
			'modelPath' => $this->modelPath,
			'values' => $values,
			'dryRunToken' => null,
			'title' => "Add New",
			'topTitle' => CurrentRoute::getTitleOf($this->type),
			'listenerDataSource' => $this->renderListenDataSource($tableToLoadDataSource),
			'listeners2' => $this->getListeners2($this->type),
			'filters2' => $this->getFilters2($this->type),
			'listeners4' => $this->getListeners4($tableBluePrint),
			'filters4' => $this->getFilters4($tableBluePrint),
			'disallowed' => $disallowed,
			'app' => LibApps::getFor($this->type),
			'hasReadOnly' => false,
			'redirect' => null,
		]);
	}
}
