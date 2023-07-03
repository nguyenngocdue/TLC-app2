<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\User_discipline;
use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\DefaultValues;
use App\Utils\Support\Json\Definitions;
use App\View\Components\Controls\DisallowedDirectCreationChecker;
use App\View\Components\Formula\All_DocId;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait TraitEntityCRUDCreateEdit2
{
	use TraitEntityListenDataSource;
	use TraitSupportEntityCRUDCreateEdit2;
	use TraitSupportPermissionGate;

	public function create(Request $request)
	{
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
			'props' => $props,
			'item' => (object)[],
			'defaultValues' => DefaultValues::getAllOf($this->type),
			'hasStatusColumn' => $hasStatusColumn,
			'type' => $this->type,
			'docId' => null,
			'action' => __FUNCTION__,
			'modelPath' => $this->data,
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
		]);
	}


	public function edit(Request $request, $id, $title = null, $topTitle = null, $readOnly = false)
	{
		//check permission using gate
		$original = $this->checkPermissionUsingGate($id, 'edit');
		$status = $request->query('status');
		$dryRunTokenRequest = $request->query('dryrun_token');
		$valueCreateDryToken = $this->hashDryRunToken($id, $status);
		$this->checkDryRunToken($dryRunTokenRequest, $valueCreateDryToken);
		// dump(SuperProps::getFor($this->type));
		$superProps = $this->getSuperProps();
		$props = $superProps['props'];
		$values = (object) $this->loadValueOfOracyPropsAndAttachments($original, $props);
		$tableBluePrint = $this->makeTableBluePrint($props);
		$tableToLoadDataSource = [...array_values($tableBluePrint), $this->type];
		$hasStatusColumn = Schema::hasColumn(Str::plural($this->type), 'status');
		$hasDocID = All_DocId::getAllEntityHasDocId($this->type);
		$docId = $hasDocID ? Str::markDocId($this->data::find($id)) : null;
		return view('dashboards.pages.entity-create-edit', [
			'superProps' => $superProps,
			'props' => $props,
			'item' => $original,
			'defaultValues' => DefaultValues::getAllOf($this->type),
			'values' => $values,
			'docId' => $docId,
			'status' => $status,
			'dryRunToken' => Hash::make($valueCreateDryToken),
			'hasStatusColumn' => $hasStatusColumn,
			'type' => Str::plural($this->type),
			'action' => __FUNCTION__,
			'modelPath' => $this->data,
			'title' => $title ?? "Edit",
			'topTitle' => $topTitle ?? CurrentRoute::getTitleOf($this->type),
			'listenerDataSource' => $this->renderListenDataSource($tableToLoadDataSource),
			'listeners2' => $this->getListeners2($this->type),
			'filters2' => $this->getFilters2($this->type),
			'listeners4' => $this->getListeners4($tableBluePrint),
			'filters4' => $this->getFilters4($tableBluePrint),
			'disallowed' => false,
			'app' => LibApps::getFor($this->type),
			'hasReadOnly' => $readOnly,
		]);
	}
}
