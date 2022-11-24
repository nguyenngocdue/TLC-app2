<?php

namespace App\Http\Controllers\Render\Attachments;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Attachment;

class AttachmentsViewAllController extends ViewAllController
{
    protected $type = 'attachment';
    protected $typeModel = Attachment::class;
    protected $permissionMiddleware = [
        'read' => 'read-attachments',
        'edit' => 'read-attachments|create-attachments|edit-attachments|edit-others-attachments',
        'delete' => 'read-attachments|create-attachments|edit-attachments|edit-others-attachments|delete-attachments|delete-others-attachments'
    ];
}
