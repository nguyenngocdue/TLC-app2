<?php

namespace App\Http\Controllers\Entities\Attachment_category;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Attachment_category;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'attachment_category';
    protected $typeModel = Attachment_category::class;
}
