<?php

namespace App\Http\Controllers\Render\Media;

use App\Http\Controllers\Render\RenderController;
use App\Models\Media;

class MediaRenderController extends RenderController
{
    protected $type = 'media';
    protected $typeModel = Media::class;
    protected $permissionMiddleware = [
        'read' => 'read_media|edit_media|edit_other_media|delete_media|delete_other_media',
        'edit' => 'edit_media|edit_other_media',
        'delete' => 'delete_media|delete_other_media'
    ];
}
