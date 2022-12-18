<?php

namespace App\Http\Controllers\Entities\Attachment;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Attachment;

class EditController extends AbstractCreateEditController
{
    protected $type = 'attachment';
    protected $data = Attachment::class;
    protected $action = "edit";

}