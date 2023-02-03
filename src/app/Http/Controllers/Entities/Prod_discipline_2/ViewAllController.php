<?php

namespace App\Http\Controllers\Entities\Prod_discipline_2;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Prod_discipline_2;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'prod_discipline_2';
    protected $typeModel = Prod_discipline_2::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_discipline_2s',
        'edit' => 'read-prod_discipline_2s|create-prod_discipline_2s|edit-prod_discipline_2s|edit-others-prod_discipline_2s',
        'delete' => 'read-prod_discipline_2s|create-prod_discipline_2s|edit-prod_discipline_2s|edit-others-prod_discipline_2s|delete-prod_discipline_2s|delete-others-prod_discipline_2s'
    ];
}