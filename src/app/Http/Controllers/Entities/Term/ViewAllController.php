<?php

namespace App\Http\Controllers\Entities\Term;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Term;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'term';
    protected $typeModel = Term::class;
    protected $permissionMiddleware = [
        'read' => 'read-terms',
        'edit' => 'read-terms|create-terms|edit-terms|edit-others-terms',
        'delete' => 'read-terms|create-terms|edit-terms|edit-others-terms|delete-terms|delete-others-terms'
    ];
}