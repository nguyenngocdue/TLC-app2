<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDCreate2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDDestroy;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDEdit2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShow2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDStore2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDUpdate2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Http\Services\UploadService2;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\Log;

class EntityCRUDController extends Controller
{
	use TraitEntityCRUDCreate2;
	use TraitEntityCRUDEdit2;

	use TraitEntityCRUDStore2;
	use TraitEntityCRUDUpdate2;

	use TraitEntityCRUDShow2;

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
}
