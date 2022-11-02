<?php

namespace App\Http\Controllers\Render\Media_categories;

use App\Http\Controllers\Render\RenderController;
use App\Models\Media_category;

class Media_categoriesRenderController extends RenderController
{
    protected $type = 'media_category';
    protected $typeModel = Media_category::class;
    protected $permissionMiddleware = [
        'read' => 'read-media_categories',
        'edit' => 'read-media_categories|create-media_categories|edit-media_categories|edit-others-media_categories',
        'delete' => 'read-media_categories|create-media_categories|edit-media_categories|edit-others-media_categories|delete-media_categories|delete-others-media_categories'
    ];
}