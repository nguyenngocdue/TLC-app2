<?php

namespace App\Http\Controllers\Manage\Medium;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Media;

class ManageMediumPropController extends ManagePropController
{
    protected $type = 'medium';
    protected $typeModel = Media::class;
}
