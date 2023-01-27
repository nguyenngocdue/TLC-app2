<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Qaqc_insp_tmpl;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'qaqc_insp_tmpl';
    protected $data = Qaqc_insp_tmpl::class;
}