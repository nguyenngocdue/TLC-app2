<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst_line;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Qaqc_insp_chklst_line;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'qaqc_insp_chklst_line';
    protected $typeModel = Qaqc_insp_chklst_line::class;
}
