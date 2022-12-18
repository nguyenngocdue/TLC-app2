<?php

namespace App\Http\Controllers\Entities\Attachment_category;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Attachment_category;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'attachment_category';
    protected $typeModel = Attachment_category::class;
    protected $permissionMiddleware = [
        'read' => 'read-attachment_categories',
        'edit' => 'read-attachment_categories|create-attachment_categories|edit-attachment_categories|edit-others-attachment_categories',
        'delete' => 'read-attachment_categories|create-attachment_categories|edit-attachment_categories|edit-others-attachment_categories|delete-attachment_categories|delete-others-attachment_categories'
    ];
}