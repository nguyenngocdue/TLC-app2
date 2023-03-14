<?php

namespace App\Http\Controllers\Entities\Workplace;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Workplace;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
    protected $permissionMiddleware = [
        'read' => 'read-workplaces',
        'edit' => 'read-workplaces|create-workplaces|edit-workplaces|edit-others-workplaces',
        'delete' => 'read-workplaces|create-workplaces|edit-workplaces|edit-others-workplaces|delete-workplaces|delete-others-workplaces'
    ];
}