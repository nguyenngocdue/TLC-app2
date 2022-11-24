<?php

namespace App\Http\Controllers\Manage\Attachment;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Attachment;

class PropController extends ManagePropController
{
    protected $type = 'attachment';
    protected $typeModel = Attachment::class;
}
