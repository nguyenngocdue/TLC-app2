<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Workflow\LibApps;
use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\DefaultValues;
use App\View\Components\Controls\DisallowedDirectCreationChecker;
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
		$values =  (object) array_merge($request->input(), $this->loadValueOfOrphanAttachments($props));
		$tableBluePrint = $this->makeTableBluePrint($props);
		$tableToLoadDataSource = [...array_values($tableBluePrint), $this->type];
		$isCheckColumnStatus = Schema::hasColumn(Str::plural($this->type), 'status');

		// $disallowed = DisallowedDirectCreationChecker::check($this->type);
		$disallowed = DisallowedDirectCreationChecker::checkAgainstRequest($request, $this->type);
		// dd($disallowed);
		if ($disallowed) {
			$creationLinks = DisallowedDirectCreationChecker::getCreationLinks($this->type);
			abort(403, "Please create via $creationLinks.");
		}

		return view('dashboards.pages.entity-create-edit', [
			'superProps' => $superProps,
			'props' => $props,
			'item' => (object)[],
			'defaultValues' => DefaultValues::getAllOf($this->type),
			'isCheckColumnStatus' => $isCheckColumnStatus,
			'type' => $this->type,
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


	public function edit(Request $request, $id)
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
		$isCheckColumnStatus = Schema::hasColumn(Str::plural($this->type), 'status');

		return view('dashboards.pages.entity-create-edit', [
			'superProps' => $superProps,
			'props' => $props,
			'item' => $original,
			'defaultValues' => DefaultValues::getAllOf($this->type),
			'values' => $values,
			'status' => $status,
			'dryRunToken' => Hash::make($valueCreateDryToken),
			'isCheckColumnStatus' => $isCheckColumnStatus,
			'type' => Str::plural($this->type),
			'action' => __FUNCTION__,
			'modelPath' => $this->data,
			'title' => "Edit",
			'topTitle' => CurrentRoute::getTitleOf($this->type),
			'listenerDataSource' => $this->renderListenDataSource($tableToLoadDataSource),
			'listeners2' => $this->getListeners2($this->type),
			'filters2' => $this->getFilters2($this->type),
			'listeners4' => $this->getListeners4($tableBluePrint),
			'filters4' => $this->getFilters4($tableBluePrint),
			'disallowed' => false,
			'app' => LibApps::getFor($this->type),
		]);
	}
}
