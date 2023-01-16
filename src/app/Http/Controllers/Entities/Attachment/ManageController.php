<?php

namespace App\Http\Controllers\Entities\Attachment;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Attachment;

class ManageController extends AbstractManageController
{
    protected $type = 'attachment';
    protected $typeModel = Attachment::class;
}