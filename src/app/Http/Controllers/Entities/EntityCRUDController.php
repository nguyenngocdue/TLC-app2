<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDCreateEdit2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDDestroy;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowChklstSht;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowChklst;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowProject;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowProps;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowQRApp;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDStoreUpdate2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Http\Controllers\Workflow\LibApps;
use App\Http\Services\UploadService2;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\App;

class EntityCRUDController extends Controller
{
	use TraitEntityCRUDShowProps;
	use TraitEntityCRUDShowProject;
	use TraitEntityCRUDShowQRApp;
	use TraitEntityCRUDShowChklst;
	use TraitEntityCRUDShowChklstSht;

	use TraitEntityCRUDCreateEdit2;
	use TraitEntityCRUDStoreUpdate2;

	use TraitEntityCRUDDestroy;
	use TraitEntityDynamicType;

	protected $type;
	protected $modelPath;
	protected $superProps;

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
		$this->postConstruct();
	}

	public function init($tableName)
	{
		$this->assignDynamicTypeManual($tableName);
		$this->postConstruct();
	}

	private function postConstruct()
	{
		$this->uploadService2 = new UploadService2($this->modelPath);
		$this->superProps = SuperProps::getFor($this->type);
	}

	public function getType()
	{
		return $this->type;
	}
	public function show($id_or_slug, $trashed = false)
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
				return $this->showQRApp($id_or_slug, $trashed);
			default:
				dump("Unknown how to render $show_renderer.");
		}
	}
	public function showTrashed($id_or_slug)
	{
		$this->show($id_or_slug, true);
	}
}
