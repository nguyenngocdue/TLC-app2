<?php

namespace App\Http\Controllers\Entities\Attachment_category;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Attachment_category;

class StatusController extends AbstractStatusController
{
    protected $type = 'attachment_category';
    protected $typeModel = Attachment_category::class;
}