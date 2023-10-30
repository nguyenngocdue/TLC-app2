<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Events\UpdatedEsgSheetEvent;
use App\Events\UpdatedProdSequenceEvent;
use Illuminate\Support\Facades\Log;

trait TraitUpdatedProdSequenceEvent
{
    public function emitPostUpdateEvent($id)
    {
        switch ($this->type) {
            case 'prod_sequence':
                event(new UpdatedProdSequenceEvent($this->modelPath, $id));
                break;
            case 'esg_sheet':
                event(new UpdatedEsgSheetEvent($id));
                break;
        }
    }
}
