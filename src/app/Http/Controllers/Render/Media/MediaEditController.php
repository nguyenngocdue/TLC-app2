<?php

namespace App\Http\Controllers\Render\Media;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Media;

class MediaEditController extends CreateEditController
{
    protected $type = "media";
    protected $data = Media::class;
    protected $action = "edit";
}
