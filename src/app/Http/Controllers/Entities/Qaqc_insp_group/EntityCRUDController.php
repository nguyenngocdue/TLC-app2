<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_group;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Qaqc_insp_group;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'qaqc_insp_group';
    protected $data = Qaqc_insp_group::class;
}