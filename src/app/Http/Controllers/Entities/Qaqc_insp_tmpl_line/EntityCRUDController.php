<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl_line;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Qaqc_insp_tmpl_line;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'qaqc_insp_tmpl_line';
    protected $data = Qaqc_insp_tmpl_line::class;
}