<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Qaqc_insp_chklst;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'qaqc_insp_chklst';
    protected $data = Qaqc_insp_chklst::class;
}