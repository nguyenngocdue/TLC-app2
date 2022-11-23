<?php

namespace App\Http\Controllers\Manage\Medium;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Media;

class ManageMediumStatusController extends ManageStatusController
{
    protected $type = 'medium';
    protected $typeModel = Media::class;
}
