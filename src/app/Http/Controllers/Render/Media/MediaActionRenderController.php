<?php

namespace App\Http\Controllers\Render\Media;

use App\Http\Controllers\Render\ActionRenderController;

class MediaActionRenderController extends ActionRenderController
{
    protected $type = 'media';
    protected $data = App\Models\Media::class;
}
