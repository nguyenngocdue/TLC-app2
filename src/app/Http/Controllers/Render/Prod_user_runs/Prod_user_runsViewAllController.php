<?php

namespace App\Http\Controllers\Render\Prod_user_runs;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Prod_user_run;

class Prod_user_runsViewAllController extends ViewAllController
{
    protected $type = 'prod_user_run';
    protected $typeModel = Prod_user_run::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_user_runs',
        'edit' => 'read-prod_user_runs|create-prod_user_runs|edit-prod_user_runs|edit-others-prod_user_runs',
        'delete' => 'read-prod_user_runs|create-prod_user_runs|edit-prod_user_runs|edit-others-prod_user_runs|delete-prod_user_runs|delete-others-prod_user_runs'
    ];
}
