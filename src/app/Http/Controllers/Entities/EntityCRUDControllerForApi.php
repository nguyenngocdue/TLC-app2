<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitApi\TraitChangeStatusMultiple;
use App\Http\Controllers\Entities\ZZTraitApi\TraitCreateNewShort;
use App\Http\Controllers\Entities\ZZTraitApi\TraitGetLines;
use App\Http\Controllers\Entities\ZZTraitApi\TraitKanban;
use App\Http\Controllers\Entities\ZZTraitApi\TraitSearchable;
use App\Http\Controllers\Entities\ZZTraitApi\TraitStoreEmpty;
use App\Http\Controllers\Entities\ZZTraitApi\TraitUpdateShort;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFormula;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitSupportEntityCRUDCreateEdit2;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\Log;

class EntityCRUDControllerForApi extends Controller
{
	use TraitEntityDynamicType;
	use TraitEntityFormula;
	use TraitSupportEntityCRUDCreateEdit2;

	use TraitCreateNewShort;
	use TraitStoreEmpty;
	use TraitUpdateShort;
	use TraitChangeStatusMultiple;
	use TraitGetLines;
	use TraitSearchable;
	use TraitKanban;

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
