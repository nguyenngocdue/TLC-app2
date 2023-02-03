<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityAttachment;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityAttachment2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityComment;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDCreateEdit;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDCreateEdit2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDShow;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDStoreUpdate;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDStoreUpdate2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFormula;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityM2M;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityStatus;
use App\Http\Services\UploadService;
use App\Http\Services\UploadService2;
use App\Utils\Support\Json\SuperProps;

abstract class AbstractEntityCRUDController extends Controller
{
	use TraitEntityCRUDShow;
	// use TraitEntityCRUDCreateEdit;
	use TraitEntityCRUDCreateEdit2;
	// use TraitEntityCRUDStoreUpdate;
	use TraitEntityCRUDStoreUpdate2;

	use TraitEntityM2M;
	use TraitEntityAttachment;
	use TraitEntityAttachment2;
	use TraitEntityComment;
	use TraitEntityStatus;
	use TraitEntityFormula;

	protected $type;
	protected $data;
	protected $superProps;

	public function __construct(
		protected UploadService $uploadService,
		protected UploadService2 $uploadService2,
	) {
		$this->superProps = SuperProps::getFor($this->type);
	}

	public function getType()
	{
		return $this->type;
	}
}
