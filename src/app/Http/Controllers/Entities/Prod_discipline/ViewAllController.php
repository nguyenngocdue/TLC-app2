<?php

namespace App\Http\Controllers\Entities\Prod_discipline;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Prod_discipline;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'prod_discipline';
    protected $typeModel = Prod_discipline::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_disciplines',
        'edit' => 'read-prod_disciplines|create-prod_disciplines|edit-prod_disciplines|edit-others-prod_disciplines',
        'delete' => 'read-prod_disciplines|create-prod_disciplines|edit-prod_disciplines|edit-others-prod_disciplines|delete-prod_disciplines|delete-others-prod_disciplines'
    ];
}