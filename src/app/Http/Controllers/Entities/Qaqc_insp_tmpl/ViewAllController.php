<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Qaqc_insp_tmpl;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'qaqc_insp_tmpl';
    protected $typeModel = Qaqc_insp_tmpl::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_tmpls',
        'edit' => 'read-qaqc_insp_tmpls|create-qaqc_insp_tmpls|edit-qaqc_insp_tmpls|edit-others-qaqc_insp_tmpls',
        'delete' => 'read-qaqc_insp_tmpls|create-qaqc_insp_tmpls|edit-qaqc_insp_tmpls|edit-others-qaqc_insp_tmpls|delete-qaqc_insp_tmpls|delete-others-qaqc_insp_tmpls'
    ];
}