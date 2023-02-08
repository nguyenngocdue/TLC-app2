<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityAttachment;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityAttachment2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityComment;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDCreateEdit2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDDestroy;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShow;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDStoreUpdate2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityM2M;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityStatus;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\App;

abstract class AbstractEntityCRUDController extends Controller
{
	use TraitEntityCRUDShow;
	use TraitEntityCRUDCreateEdit2;
	use TraitEntityCRUDStoreUpdate2;
	use TraitEntityCRUDDestroy;

	use TraitEntityM2M;
	use TraitEntityAttachment;
	use TraitEntityAttachment2;
	use TraitEntityComment;
	use TraitEntityStatus;

	protected $type;
	protected $data;
	protected $superProps;

	protected $uploadService;
	protected $uploadService2;

	//construction has to be no argument as for further instantiation of EditableTable
	public function __construct()
	{
		$this->uploadService = App::make('App\Http\Services\UploadService');
		$this->uploadService2 = App::make('App\Http\Services\UploadService2');
		$this->superProps = SuperProps::getFor($this->type);
	}

	public function getType()
	{
		return $this->type;
	}
}
