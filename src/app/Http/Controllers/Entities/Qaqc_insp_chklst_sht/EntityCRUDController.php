<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst_sht;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Qaqc_insp_chklst_sht;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'qaqc_insp_chklst_sht';
    protected $data = Qaqc_insp_chklst_sht::class;
}