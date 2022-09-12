<?php

namespace App\Http\Controllers\Render\Media;

use App\Http\Controllers\Render\RenderController;
use App\Models\Media;

class MediaRenderController extends RenderController
{
    protected $type = 'media';
    protected $typeModel = Media::class;
}
