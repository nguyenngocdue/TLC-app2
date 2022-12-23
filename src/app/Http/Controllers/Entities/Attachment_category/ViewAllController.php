<?php

namespace App\Http\Controllers\Entities\Attachment_category;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Attachment_category;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'attachment_category';
    protected $typeModel = Attachment_category::class;
    protected $permissionMiddleware = [
        'read' => 'read-fields',
        'edit' => 'read-fields|create-fields|edit-fields|edit-others-fields',
        'delete' => 'read-fields|create-fields|edit-fields|edit-others-fields|delete-fields|delete-others-fields'
    ];
}
