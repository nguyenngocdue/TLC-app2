<?php

namespace App\Http\Controllers\Manage\Attachment_category;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Attachment_category;

class PropController extends ManagePropController
{
    protected $type = 'attachment_category';
    protected $typeModel = Attachment_category::class;
}
