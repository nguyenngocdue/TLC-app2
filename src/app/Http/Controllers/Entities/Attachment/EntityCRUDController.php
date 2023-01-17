<?php

namespace App\Http\Controllers\Entities\Attachment;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Attachment;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'attachment';
    protected $data = Attachment::class;
}