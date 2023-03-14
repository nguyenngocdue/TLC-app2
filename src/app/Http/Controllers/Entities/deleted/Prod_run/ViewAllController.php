<?php

namespace App\Http\Controllers\Entities\Prod_run;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Prod_run;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'prod_run';
    protected $typeModel = Prod_run::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_runs',
        'edit' => 'read-prod_runs|create-prod_runs|edit-prod_runs|edit-others-prod_runs',
        'delete' => 'read-prod_runs|create-prod_runs|edit-prod_runs|edit-others-prod_runs|delete-prod_runs|delete-others-prod_runs'
    ];
}