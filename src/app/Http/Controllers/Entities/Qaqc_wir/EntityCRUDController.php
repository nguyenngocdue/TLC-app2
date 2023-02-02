<?php

namespace App\Http\Controllers\Entities\Qaqc_wir;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Qaqc_wir;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'qaqc_wir';
    protected $data = Qaqc_wir::class;
}