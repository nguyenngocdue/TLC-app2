<?php

namespace App\Http\Controllers\Entities\Attachment;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Attachment;

class PropController extends AbstractPropController
{
    protected $type = 'attachment';
    protected $typeModel = Attachment::class;
}
