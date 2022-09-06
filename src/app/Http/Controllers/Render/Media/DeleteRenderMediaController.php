<?php

namespace App\Http\Controllers\Render\Media;

use App\Http\Controllers\Render\DeleteRenderController;

class DeleteRenderMediaController extends DeleteRenderController
{
    protected $type = 'media';
    protected $data = App\Models\Media::class;
}
