<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl_run;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Qaqc_insp_tmpl_run;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'qaqc_insp_tmpl_run';
    protected $typeModel = Qaqc_insp_tmpl_run::class;
}
