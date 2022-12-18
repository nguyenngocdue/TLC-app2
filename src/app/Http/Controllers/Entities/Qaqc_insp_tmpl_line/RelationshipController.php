<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl_line;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Qaqc_insp_tmpl_line;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'qaqc_insp_tmpl_line';
    protected $typeModel = Qaqc_insp_tmpl_line::class;
}
