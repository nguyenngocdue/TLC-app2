<?php

namespace App\Http\Controllers\Entities\Attachment;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Attachment;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'attachment';
    protected $typeModel = Attachment::class;
}
