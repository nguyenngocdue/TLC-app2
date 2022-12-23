<?php

namespace App\Http\Controllers\Entities\Comment_category;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Comment_category;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'comment_category';
    protected $typeModel = Comment_category::class;
    protected $permissionMiddleware = [
        'read' => 'read-field',
        'edit' => 'read-field|create-field|edit-field|edit-others-field',
        'delete' => 'read-field|create-field|edit-field|edit-others-field|delete-field|delete-others-field'
    ];
}
