<?php

namespace App\Http\Controllers\Entities\Attachment_category;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Attachment_category;

class EditController extends AbstractCreateEditController
{
    protected $type = 'attachment_category';
    protected $data = Attachment_category::class;
    protected $action = "edit";

}