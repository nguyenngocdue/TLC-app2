<?php

namespace App\Listeners;

use App\Events\EntityCreatedEvent;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFormula;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShouldUpdateFieldsListener implements ShouldQueue
{
    use TraitEntityFormula;
    /**
     * Handle the event.
     *
     * @param  \App\Events\EntityCreatedEvent  $event
     * @return void
     */
    public function handle(EntityCreatedEvent $event)
    {
        // $id = $event->dataEvent['id'];
        // $type = $event->dataEvent['type'];

        // $instanceDB = DB::table(Str::plural($type))->where('id', $id);
        // $itemDB = json_decode($instanceDB->get(), true)[0];
        // $itemDB['name'] = $itemDB['name'] ?? "";

        // dump("In Should Update");
        // $itemDB = $this->apply_formula($itemDB, $type);

        // $instanceDB->update([$prop['column_name'] => $value]);
    }
}
