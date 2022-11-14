<?php

namespace App\Http\Controllers\Manage\Medium;

use App\Http\Controllers\Manage\ManageTablePropController;

class ManageMediumTablePropController extends ManageTablePropController
{
    protected $type = 'medium';
    protected $typeModel = Media::class;
}
