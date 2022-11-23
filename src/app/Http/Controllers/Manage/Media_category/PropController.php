<?php

namespace App\Http\Controllers\Manage\Media_category;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Media_category;

class PropController extends ManagePropController
{
    protected $type = 'media_category';
    protected $typeModel = Media_category::class;
}
