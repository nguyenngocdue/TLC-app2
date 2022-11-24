<?php

namespace App\Http\Controllers\Manage\Attachment;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Attachment;

class TablePropController extends ManageTablePropController
{
    protected $type = 'attachment';
    protected $typeModel = Attachment::class;
}
