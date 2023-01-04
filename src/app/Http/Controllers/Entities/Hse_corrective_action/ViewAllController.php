<?php

namespace App\Http\Controllers\Entities\Hse_corrective_action;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Hse_corrective_action;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'hse_corrective_action';
    protected $typeModel = Hse_corrective_action::class;
    protected $permissionMiddleware = [
        'read' => 'read-hse_corrective_actions',
        'edit' => 'read-hse_corrective_actions|create-hse_corrective_actions|edit-hse_corrective_actions|edit-others-hse_corrective_actions',
        'delete' => 'read-hse_corrective_actions|create-hse_corrective_actions|edit-hse_corrective_actions|edit-others-hse_corrective_actions|delete-hse_corrective_actions|delete-others-hse_corrective_actions'
    ];
}