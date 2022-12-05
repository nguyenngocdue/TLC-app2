<?php

namespace App\Http\Controllers\Manage\Qaqc_insp_master_list;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Qaqc_insp_master_list;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'qaqc_insp_master_list';
    protected $typeModel = Qaqc_insp_master_list::class;
}
