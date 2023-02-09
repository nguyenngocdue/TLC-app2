<?php

namespace App\Http\Controllers\Entities\Pj_unit;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Pj_unit;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'pj_unit';
    protected $typeModel = Pj_unit::class;
    protected $permissionMiddleware = [
        'read' => 'read-pj_units',
        'edit' => 'read-pj_units|create-pj_units|edit-pj_units|edit-others-pj_units',
        'delete' => 'read-pj_units|create-pj_units|edit-pj_units|edit-others-pj_units|delete-pj_units|delete-others-pj_units'
    ];
}