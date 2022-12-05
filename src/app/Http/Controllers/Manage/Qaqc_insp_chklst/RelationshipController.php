<?php

namespace App\Http\Controllers\Manage\Qaqc_insp_chklst;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Qaqc_insp_chklst;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'qaqc_insp_chklst';
    protected $typeModel = Qaqc_insp_chklst::class;
}
