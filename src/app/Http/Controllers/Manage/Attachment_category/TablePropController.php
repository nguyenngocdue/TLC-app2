<?php

namespace App\Http\Controllers\Manage\Attachment_category;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Attachment_category;

class TablePropController extends ManageTablePropController
{
    protected $type = 'attachment_category';
    protected $typeModel = Attachment_category::class;
}
