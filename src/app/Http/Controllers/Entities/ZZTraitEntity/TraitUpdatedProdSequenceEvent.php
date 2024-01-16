<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Events\UpdatedEsgSheetEvent;
use App\Events\UpdatedProdSequenceEvent;
use App\Events\UpdatedQaqcChklstSheetEvent;
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
            case 'qaqc_insp_chklst_sht':
                event(new UpdatedQaqcChklstSheetEvent($id));
                break;
            default:
                // Log::info("Updated " . $this->type);
                break;
        }
    }
}
