<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDStoreUpdate2Api;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Utils\Support\Json\SuperProps;

class EntityCRUDControllerForApi extends Controller
{
	use TraitEntityCRUDStoreUpdate2Api;
	use TraitEntityDynamicType;

	protected $type;
	protected $data;
	protected $superProps;

	protected $uploadService2;

	//construction has to be no argument as for further instantiation of EditableTable
	public function __construct()
	{
		$this->assignDynamicTypeCreateEditForApi();

		// $this->uploadService2 = App::make('App\Http\Services\UploadService2');

		$this->superProps = SuperProps::getFor($this->type);
	}

	// public function getType()
	// {
	// 	return $this->type;
	// }
}
