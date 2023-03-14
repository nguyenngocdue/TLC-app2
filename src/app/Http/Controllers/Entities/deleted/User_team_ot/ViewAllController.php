<?php

namespace App\Http\Controllers\Entities\User_team_ot;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\User_team_ot;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'user_team_ot';
    protected $typeModel = User_team_ot::class;
    protected $permissionMiddleware = [
        'read' => 'read-user_team_ots',
        'edit' => 'read-user_team_ots|create-user_team_ots|edit-user_team_ots|edit-others-user_team_ots',
        'delete' => 'read-user_team_ots|create-user_team_ots|edit-user_team_ots|edit-others-user_team_ots|delete-user_team_ots|delete-others-user_team_ots'
    ];
}