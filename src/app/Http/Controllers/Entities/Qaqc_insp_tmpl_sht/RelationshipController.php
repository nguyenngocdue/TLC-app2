<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl_sht;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Qaqc_insp_tmpl_sht;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'qaqc_insp_tmpl_sht';
    protected $typeModel = Qaqc_insp_tmpl_sht::class;
}
