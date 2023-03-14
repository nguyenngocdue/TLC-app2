<?php

namespace App\Http\Controllers\Entities\Prod_sequence;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Prod_sequence;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'prod_sequence';
    protected $typeModel = Prod_sequence::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_sequences',
        'edit' => 'read-prod_sequences|create-prod_sequences|edit-prod_sequences|edit-others-prod_sequences',
        'delete' => 'read-prod_sequences|create-prod_sequences|edit-prod_sequences|edit-others-prod_sequences|delete-prod_sequences|delete-others-prod_sequences'
    ];
}