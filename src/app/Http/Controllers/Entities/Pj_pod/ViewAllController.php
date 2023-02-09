<?php

namespace App\Http\Controllers\Entities\Pj_pod;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Pj_pod;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'pj_pod';
    protected $typeModel = Pj_pod::class;
    protected $permissionMiddleware = [
        'read' => 'read-pj_pods',
        'edit' => 'read-pj_pods|create-pj_pods|edit-pj_pods|edit-others-pj_pods',
        'delete' => 'read-pj_pods|create-pj_pods|edit-pj_pods|edit-others-pj_pods|delete-pj_pods|delete-others-pj_pods'
    ];
}