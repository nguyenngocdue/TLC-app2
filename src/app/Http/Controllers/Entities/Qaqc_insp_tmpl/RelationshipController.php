<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Qaqc_insp_tmpl;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'qaqc_insp_tmpl';
    protected $typeModel = Qaqc_insp_tmpl::class;
}
