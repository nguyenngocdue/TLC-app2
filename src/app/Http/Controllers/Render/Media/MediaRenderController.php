<?php

namespace App\Http\Controllers\Render\Media;

use App\Http\Controllers\Render\RenderController;
use App\Models\Media;

class MediaRenderController extends RenderController
{
    protected $type = 'media';
    protected $typeModel = Media::class;
    protected $permissionMiddleware = [
        'read' => 'read-media',
        'edit' => 'read-media|create-media|edit-media|edit-others-media',
        'delete' => 'read-media|create-media|edit-media|edit-others-media|delete-media|delete-others-media'
    ];
}
