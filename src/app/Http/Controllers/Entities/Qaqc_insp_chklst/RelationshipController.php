<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Qaqc_insp_chklst;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'qaqc_insp_chklst';
    protected $typeModel = Qaqc_insp_chklst::class;
}
