<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Events\UpdatedSequenceBaseEvent;

trait TraitUpdatedProdSequenceEvent
{

    public function eventUpdatedProdSequence($id)
    {
        if($this->type == 'prod_sequence') event(new UpdatedSequenceBaseEvent($this->data,$id));
    }
}
