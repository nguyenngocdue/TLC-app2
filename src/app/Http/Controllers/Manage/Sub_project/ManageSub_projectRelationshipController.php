<?php

namespace App\Http\Controllers\Manage\Sub_project;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Sub_project;

class ManageSub_projectRelationshipController extends ManageRelationshipController
{
    protected $type = 'sub_project';
    protected $typeModel = Sub_project::class;
}
