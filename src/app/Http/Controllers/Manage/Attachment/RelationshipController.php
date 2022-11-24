<?php

namespace App\Http\Controllers\Manage\Attachment;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Attachment;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'attachment';
    protected $typeModel = Attachment::class;
}
