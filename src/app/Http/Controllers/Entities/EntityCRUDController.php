<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDCreate2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDDestroy;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDEdit2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowChklstSht;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowChklst;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowProject;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowProps;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowQRLandingPageModule;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowQRLandingPageUnit;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowReport;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDStore2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDUpdate2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Http\Controllers\Workflow\LibApps;
use App\Http\Services\UploadService2;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EntityCRUDController extends Controller
{
	use TraitEntityCRUDShowProps;
	use TraitEntityCRUDShowProject;
	use TraitEntityCRUDShowReport;
	use TraitEntityCRUDShowQRLandingPageModule;
	use TraitEntityCRUDShowQRLandingPageUnit;
	use TraitEntityCRUDShowChklst;
	use TraitEntityCRUDShowChklstSht;

	use TraitEntityCRUDCreate2;
	use TraitEntityCRUDEdit2;

	use TraitEntityCRUDStore2;
	use TraitEntityCRUDUpdate2;

	use TraitEntityCRUDDestroy;
	use TraitEntityDynamicType;

	protected $type;
	protected $modelPath;

	protected $uploadService2;
	protected $permissionMiddleware;

	//construction has to be no argument as for further instantiation of EditableTable
	public function __construct()
	{
		$this->assignDynamicTypeCreateEdit();
		if (!in_array($this->type, qr_apps_renderer()))
			$this->middleware("permission:{$this->permissionMiddleware['read']}")->only('show');
		$this->middleware("permission:{$this->permissionMiddleware['create']}")->only('create');
		$this->middleware("permission:{$this->permissionMiddleware['edit']}")->only('edit', 'store', 'update');
		$this->middleware("permission:{$this->permissionMiddleware['delete']}")->only('destroy', 'destroyMultiple');
		// Log::info(Auth::user());
		// Log::info($request->getUser());
		$this->postConstruct();
	}


	protected function superProps()
	{
		return SuperProps::getFor($this->type);
	}

	public function init($tableName)
	{
		$this->assignDynamicTypeManual($tableName);
		$this->postConstruct();
	}

	private function postConstruct()
	{
		$this->uploadService2 = new UploadService2($this->modelPath);
		// $this->superProps = SuperProps::getFor($this->type);
	}

	public function getType()
	{
		return $this->type;
	}
	public function show(Request $request, $id_or_slug, $trashed = false)
	{
		$app = LibApps::getFor($this->type);
		$show_renderer = $app['show_renderer'];
		switch ($show_renderer) {
			case '':
				return $this->showProps($id_or_slug, $trashed);
			case 'project-renderer':
				return $this->showProject($id_or_slug, $trashed);
			case 'checklist-renderer':
				return $this->showChklst($id_or_slug, $trashed);
			case 'checklist-sheet-renderer':
				return $this->showChklstSht($id_or_slug, $trashed);
			case 'qr-app-renderer':
				switch ($this->type) {
					case 'pj_module':
						return $this->showQRAppModule($id_or_slug, $trashed);
					case 'pj_unit':
						return $this->showQRAppUnit($id_or_slug, $trashed);
					default:
				}
			case 'report-renderer':
				return $this->showReport($request, $id_or_slug, $trashed);
			default:
				dump("Unknown how to render $show_renderer.");
		}
	}
	public function showTrashed(Request $request, $id_or_slug)
	{
		$this->show($request, $id_or_slug, true);
	}
}
