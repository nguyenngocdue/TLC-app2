<?php

namespace App\Http\Controllers\Manage\Medium;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Media;

class ManageMediumTablePropController extends ManageTablePropController
{
    protected $type = 'medium';
    protected $typeModel = Media::class;
}
