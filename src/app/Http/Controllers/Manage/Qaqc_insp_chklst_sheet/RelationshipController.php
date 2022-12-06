<?php

namespace App\Http\Controllers\Manage\Qaqc_insp_chklst_sheet;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Qaqc_insp_chklst_sheet;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'qaqc_insp_chklst_sheet';
    protected $typeModel = Qaqc_insp_chklst_sheet::class;
}
