<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDCreateEdit2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDDestroy;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShow;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShowQR;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDStoreUpdate2;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\App;

abstract class AbstractEntityCRUDController extends Controller
{
	use TraitEntityCRUDShow;
	use TraitEntityCRUDShowQR;
	use TraitEntityCRUDCreateEdit2;
	use TraitEntityCRUDStoreUpdate2;
	use TraitEntityCRUDDestroy;

	protected $type;
	protected $data;
	protected $superProps;

	protected $uploadService2;

	//construction has to be no argument as for further instantiation of EditableTable
	public function __construct()
	{
		$this->uploadService2 = App::make('App\Http\Services\UploadService2');
		$this->superProps = SuperProps::getFor($this->type);
	}

	public function getType()
	{
		return $this->type;
	}
}
