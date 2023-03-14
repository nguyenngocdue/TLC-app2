<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Qaqc_insp_chklst;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'qaqc_insp_chklst';
    protected $typeModel = Qaqc_insp_chklst::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_chklsts',
        'edit' => 'read-qaqc_insp_chklsts|create-qaqc_insp_chklsts|edit-qaqc_insp_chklsts|edit-others-qaqc_insp_chklsts',
        'delete' => 'read-qaqc_insp_chklsts|create-qaqc_insp_chklsts|edit-qaqc_insp_chklsts|edit-others-qaqc_insp_chklsts|delete-qaqc_insp_chklsts|delete-others-qaqc_insp_chklsts'
    ];
}