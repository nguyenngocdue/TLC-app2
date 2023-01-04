<?php

namespace App\Http\Traits;

use App\Models\Attachment;

trait HasAttachments
{
    function attachment()
    {
        return $this->morphMany(Attachment::class, 'attachable', 'object_type', 'object_id');
    }
}
