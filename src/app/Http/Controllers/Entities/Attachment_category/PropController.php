<?php

namespace App\Http\Controllers\Entities\Attachment_category;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Attachment_category;

class PropController extends AbstractPropController
{
    protected $type = 'attachment_category';
    protected $typeModel = Attachment_category::class;
}
