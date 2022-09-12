<?php

namespace App\Http\Controllers\Render\Media;

use App\Http\Controllers\Render\ActionRenderController;
use App\Models\Media;

class MediaActionRenderController extends ActionRenderController
{
    protected $type = 'media';
    protected $typeModel = Media::class;
}
