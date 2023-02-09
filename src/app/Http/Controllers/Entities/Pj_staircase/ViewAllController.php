<?php

namespace App\Http\Controllers\Entities\Pj_staircase;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Pj_staircase;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'pj_staircase';
    protected $typeModel = Pj_staircase::class;
    protected $permissionMiddleware = [
        'read' => 'read-pj_staircases',
        'edit' => 'read-pj_staircases|create-pj_staircases|edit-pj_staircases|edit-others-pj_staircases',
        'delete' => 'read-pj_staircases|create-pj_staircases|edit-pj_staircases|edit-others-pj_staircases|delete-pj_staircases|delete-others-pj_staircases'
    ];
}