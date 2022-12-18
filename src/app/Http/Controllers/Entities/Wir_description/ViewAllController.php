<?php

namespace App\Http\Controllers\Entities\Wir_description;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Wir_description;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'wir_description';
    protected $typeModel = Wir_description::class;
    protected $permissionMiddleware = [
        'read' => 'read-wir_descriptions',
        'edit' => 'read-wir_descriptions|create-wir_descriptions|edit-wir_descriptions|edit-others-wir_descriptions',
        'delete' => 'read-wir_descriptions|create-wir_descriptions|edit-wir_descriptions|edit-others-wir_descriptions|delete-wir_descriptions|delete-others-wir_descriptions'
    ];
}