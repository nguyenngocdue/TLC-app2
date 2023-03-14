<?php

namespace App\Http\Controllers\Entities\Attachment;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Attachment;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'attachment111';
    protected $typeModel = Attachment::class . '111';
}
