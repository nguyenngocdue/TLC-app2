<?php

namespace App\Http\Controllers\Entities\Pj_module;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Pj_module;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'pj_module';
    protected $typeModel = Pj_module::class;
    protected $permissionMiddleware = [
        'read' => 'read-pj_modules',
        'edit' => 'read-pj_modules|create-pj_modules|edit-pj_modules|edit-others-pj_modules',
        'delete' => 'read-pj_modules|create-pj_modules|edit-pj_modules|edit-others-pj_modules|delete-pj_modules|delete-others-pj_modules'
    ];
}