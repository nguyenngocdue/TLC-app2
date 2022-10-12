<?php

namespace App\Http\Controllers\Manage\Work_mode;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Work_mode;

class ManageWork_modeRelationshipController extends ManageRelationshipController
{
    protected $type = 'work_mode';
    protected $typeModel = Work_mode::class;
}
