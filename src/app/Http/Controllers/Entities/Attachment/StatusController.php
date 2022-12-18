<?php

namespace App\Http\Controllers\Entities\Attachment;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Attachment;

class StatusController extends AbstractStatusController
{
    protected $type = 'attachment';
    protected $typeModel = Attachment::class;
}