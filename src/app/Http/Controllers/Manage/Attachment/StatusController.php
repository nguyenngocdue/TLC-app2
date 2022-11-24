<?php

namespace App\Http\Controllers\Manage\Attachment;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Attachment;

class StatusController extends ManageStatusController
{
    protected $type = 'attachment';
    protected $typeModel = Attachment::class;
}
