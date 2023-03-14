<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst_sht;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Qaqc_insp_chklst_sht;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'qaqc_insp_chklst_sht';
    protected $typeModel = Qaqc_insp_chklst_sht::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_chklst_shts',
        'edit' => 'read-qaqc_insp_chklst_shts|create-qaqc_insp_chklst_shts|edit-qaqc_insp_chklst_shts|edit-others-qaqc_insp_chklst_shts',
        'delete' => 'read-qaqc_insp_chklst_shts|create-qaqc_insp_chklst_shts|edit-qaqc_insp_chklst_shts|edit-others-qaqc_insp_chklst_shts|delete-qaqc_insp_chklst_shts|delete-others-qaqc_insp_chklst_shts'
    ];
}