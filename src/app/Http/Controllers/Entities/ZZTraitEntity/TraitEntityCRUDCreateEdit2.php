<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Attachment;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\Json\DefaultValues;
use App\Utils\Support\Json\Props;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\JsonControls;
use Database\Seeders\FieldSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Ndc\SpatieCustom\Exceptions\UnauthorizedException;

trait TraitEntityCRUDCreateEdit2
{
	use TraitEntityListenDataSource;
	use TraitSupportEntityCRUDCreateEdit2;

	public function create(Request $request)
	{
		$superProps = $this->getSuperProps();
		$props = $superProps['props'];
		$values =  (object) array_merge($request->input(), $this->loadValueOfOrphanAttachments($props));

		$tableBluePrint = $this->makeTableBluePrint($props);
		$tableToLoadDataSource = [...array_values($tableBluePrint), $this->type];
		$isCheckColumnStatus = Schema::hasColumn(Str::plural($this->type), 'status');
		return view('dashboards.pages.entity-create-edit', [
			'superProps' => $superProps,
			'props' => $props,
			'item' => (object)[],
			'defaultValues' => DefaultValues::getAllOf($this->type),
			// 'realtimes' => Realtimes::getAllOf($this->type),
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
		]);
	}
	private function checkPermissionEdit($permission)
	{
		return auth()->user()->roleSets[0]->hasAnyPermission($permission);
	}
	private function checkPermissionEditUsingGate($id)
	{
		$permissions = $this->permissionMiddleware['edit'];
		$permissions = is_array($permissions) ? $permissions : explode('|', $permissions);
		$model = (new ($this->data))::findOrFail($id);
		switch (true) {
			case $this->checkPermissionEdit($permissions[0]):
				if (!Gate::allows('edit', $model) || !Gate::allows('edit-others', $model)) abort(403);
				break;
			case $this->checkPermissionEdit($permissions[1]):
				if (!Gate::allows('edit-others', $model)) abort(403);
				break;
			default:
				break;
		}
	}

	public function edit(Request $request, $id)
	{
		//check permission using gate
		$this->checkPermissionEditUsingGate($id);
		$status = $request->query('status');
		$dryRunTokenRequest = $request->query('dryrun_token');
		$valueCreateDryToken = $this->hashDryRunToken($id, $status);
		$this->checkDryRunToken($dryRunTokenRequest, $valueCreateDryToken);
		// dump(SuperProps::getFor($this->type));
		$superProps = $this->getSuperProps();
		$props = $superProps['props'];
		$original = $this->data::findOrFail($id);
		$values = (object) $this->loadValueOfOracyPropsAndAttachments($original, $props);
		$tableBluePrint = $this->makeTableBluePrint($props);
		$tableToLoadDataSource = [...array_values($tableBluePrint), $this->type];
		$isCheckColumnStatus = Schema::hasColumn(Str::plural($this->type), 'status');
		return view('dashboards.pages.entity-create-edit', [
			'superProps' => $superProps,
			'props' => $props,
			'item' => $original,
			'defaultValues' => DefaultValues::getAllOf($this->type),
			// 'realtimes' => Realtimes::getAllOf($this->type),
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
		]);
	}
}
