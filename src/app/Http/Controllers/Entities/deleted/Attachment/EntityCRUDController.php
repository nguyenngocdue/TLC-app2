<?php

namespace App\Http\Controllers\Entities\Attachment;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Attachment;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'attachment111';
    protected $data = Attachment::class . '111';
}
