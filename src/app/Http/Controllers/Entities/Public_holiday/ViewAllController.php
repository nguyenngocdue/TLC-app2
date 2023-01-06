<?php

namespace App\Http\Controllers\Entities\Public_holiday;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Public_holiday;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'public_holiday';
    protected $typeModel = Public_holiday::class;
    protected $permissionMiddleware = [
        'read' => 'read-public_holidays',
        'edit' => 'read-public_holidays|create-public_holidays|edit-public_holidays|edit-others-public_holidays',
        'delete' => 'read-public_holidays|create-public_holidays|edit-public_holidays|edit-others-public_holidays|delete-public_holidays|delete-others-public_holidays'
    ];
}