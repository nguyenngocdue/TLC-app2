<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitApi\TraitCloneTemplate;
use App\Http\Controllers\Entities\ZZTraitApi\TraitStoreEmpty;
use App\Http\Controllers\Entities\ZZTraitApi\TraitUpdateShort;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFormula;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitSupportEntityCRUDCreateEdit2;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\Log;

class EntityCRUDControllerForApiClone extends Controller
{
	use TraitEntityDynamicType;
	use TraitEntityFormula;
	use TraitSupportEntityCRUDCreateEdit2;

	use TraitCloneTemplate;

	protected $type;
	protected $modelPath;
	protected $superProps;

	protected $uploadService2;

	//construction has to be no argument as for further instantiation of EditableTable
	public function __construct()
	{
		$this->assignDynamicTypeCreateEditForApi();
		$this->superProps = SuperProps::getFor($this->type);
	}
}
