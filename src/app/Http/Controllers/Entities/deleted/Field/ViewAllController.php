<?php

namespace App\Http\Controllers\Entities\Field;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Field;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'field';
    protected $typeModel = Field::class;
    protected $permissionMiddleware = [
        'read' => 'read-fields',
        'edit' => 'read-fields|create-fields|edit-fields|edit-others-fields',
        'delete' => 'read-fields|create-fields|edit-fields|edit-others-fields|delete-fields|delete-others-fields'
    ];
}