<?php

namespace App\Http\Controllers\Entities\Attachment;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Attachment;

class ListenerController extends AbstractListenerController
{
    protected $type = 'attachment';
    protected $typeModel = Attachment::class;
}
