<?php

namespace App\Http\Controllers\Manage\Qaqc_insp_tmpl;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Qaqc_insp_tmpl;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'qaqc_insp_tmpl';
    protected $typeModel = Qaqc_insp_tmpl::class;
}
