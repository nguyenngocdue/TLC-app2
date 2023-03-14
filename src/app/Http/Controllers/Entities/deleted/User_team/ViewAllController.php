<?php

namespace App\Http\Controllers\Entities\User_team;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\User_team;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'user_team';
    protected $typeModel = User_team::class;
    protected $permissionMiddleware = [
        'read' => 'read-user_teams',
        'edit' => 'read-user_teams|create-user_teams|edit-user_teams|edit-others-user_teams',
        'delete' => 'read-user_teams|create-user_teams|edit-user_teams|edit-others-user_teams|delete-user_teams|delete-others-user_teams'
    ];
}