<?php

namespace App\Http\Controllers\Manage\Medium;

use App\Http\Controllers\Manage\ManagePropController;

class ManageMediumPropController extends ManagePropController
{
    protected $type = 'medium';
    protected $typeModel = Media::class;
}
