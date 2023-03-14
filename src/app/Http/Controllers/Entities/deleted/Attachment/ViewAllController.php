<?php

namespace App\Http\Controllers\Entities\Attachment;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Attachment;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'attachment';
    protected $typeModel = Attachment::class;
    protected $permissionMiddleware = [
        'read' => 'read-attachments',
        'edit' => 'read-attachments|create-attachments|edit-attachments|edit-others-attachments',
        'delete' => 'read-attachments|create-attachments|edit-attachments|edit-others-attachments|delete-attachments|delete-others-attachments'
    ];
}