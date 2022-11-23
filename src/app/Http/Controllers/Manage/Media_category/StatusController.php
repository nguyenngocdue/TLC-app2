<?php

namespace App\Http\Controllers\Manage\Media_category;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Media_category;

class StatusController extends ManageStatusController
{
    protected $type = 'media_category';
    protected $typeModel = Media_category::class;
}