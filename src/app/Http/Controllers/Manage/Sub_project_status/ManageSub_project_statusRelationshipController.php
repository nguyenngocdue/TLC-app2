<?php

namespace App\Http\Controllers\Manage\Sub_project_status;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Sub_project_status;

class ManageSub_project_statusRelationshipController extends ManageRelationshipController
{
    protected $type = 'sub_project_status';
    protected $typeModel = Sub_project_status::class;
}
