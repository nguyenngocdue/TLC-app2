<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Events\OpenedDocumentEvent;
use App\Http\Controllers\Workflow\LibApps;
use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\DefaultValues;
use App\View\Components\Formula\All_DocId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait TraitEntityCRUDEdit2
{
	use TraitEntityListenDataSource;
	use TraitSupportEntityCRUDCreateEdit2;
	use TraitSupportPermissionGate;

	// protected function getEditTitle()
	// {
	// 	return "Edit";
	// }

	// protected function getEditTopTitle()
	// {
	// 	return CurrentRoute::getTitleOf($this->type);
	// }

	public function edit(Request $request, $id, $viewRender = null, $readOnly = false)
	{
		if ($this->type == 'exam_sheet') {
			return Blade::render('<x-controls.exam-sheet.exam-sheet-page-edit
				id="{{$id}}" type="{{$type}}"
			/>', [
				'id' => $id,
				'type' => $this->type,
			]);
		}

		$item = $this->modelPath::find($id);
		if ($this->type == 'qaqc_insp_chklst_sht') {
			$this->checkIsExternalInspectorAndNominated($item);
			$this->checkIsCouncilMemberAndNominated($item);
		}

		//check permission using gate
		$original = $this->checkPermissionUsingGate($id, 'edit');
		$status = $request->query('status');
		$dryRunTokenRequest = $request->query('dryrun_token');
		$valueCreateDryToken = $this->hashDryRunToken($id, $status);
		$this->checkDryRunToken($dryRunTokenRequest, $valueCreateDryToken);
		$superProps = $this->getSuperProps();
		$props = $superProps['props'];
		$values = (object) $this->loadValueOfBelongsToManyAndAttachments($original, $props);
		$tableBluePrint = $this->makeTableBluePrint($props);
		$tableToLoadDataSource = [...array_values($tableBluePrint), $this->type];
		$hasStatusColumn = Schema::hasColumn(Str::plural($this->type), 'status');
		$hasDocID = All_DocId::getAllEntityHasDocId($this->type);

		$docId = $hasDocID ? Str::markDocId($item) : null;
		event(new OpenedDocumentEvent($this->type, $id, CurrentUser::id()));

		return view('dashboards.pages.entity-create-edit', [
			'superProps' => $superProps,
			'item' => $original,
			'defaultValues' => DefaultValues::getAllOf($this->type),
			'values' => $values,
			'docId' => $docId,
			'status' => $status,
			'dryRunToken' => Hash::make($valueCreateDryToken),
			'hasStatusColumn' => $hasStatusColumn,
			'type' => Str::plural($this->type),
			'action' => __FUNCTION__,
			'modelPath' => $this->modelPath,
			'title' => $this->getEditTitle(), //$title ?? "Edit",
			'topTitle' => $this->getEditTopTitle(), //$topTitle ?? CurrentRoute::getTitleOf($this->type),
			'listenerDataSource' => $this->renderListenDataSource($tableToLoadDataSource), // query allowed memory usage
			'listeners2' => $this->getListeners2($this->type),
			'filters2' => $this->getFilters2($this->type),
			'listeners4' => $this->getListeners4($tableBluePrint),
			'filters4' => $this->getFilters4($tableBluePrint),
			'disallowed' => false,
			'app' => LibApps::getFor($this->type),
			'hasReadOnly' => $readOnly,
			'redirect' => $viewRender,
		]);
	}
}
