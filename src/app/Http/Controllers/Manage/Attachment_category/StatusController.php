<?php

namespace App\Http\Controllers\Manage\Attachment_category;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Attachment_category;

class StatusController extends ManageStatusController
{
    protected $type = 'attachment_category';
    protected $typeModel = Attachment_category::class;
}
