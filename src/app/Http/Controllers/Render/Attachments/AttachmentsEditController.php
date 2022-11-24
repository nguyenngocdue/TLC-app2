<?php

namespace App\Http\Controllers\Render\Attachments;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Attachment;

class AttachmentsEditController extends CreateEditController
{
    protected $type = "attachment";
    protected $data = Attachment::class;
    protected $action = "edit";
}
