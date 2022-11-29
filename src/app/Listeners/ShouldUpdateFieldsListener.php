<?php

namespace App\Listeners;

use App\Events\EntityCreatedEvent;
use App\Helpers\Helper;
use App\Http\Services\ReadingFileService;
use App\View\Components\Formula\All_ConcatNameWith123;
use App\View\Components\Formula\All_SlugifyByName;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShouldUpdateFieldsListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\EntityCreatedEvent  $event
     * @return void
     */
    public function handle(EntityCreatedEvent $event)
    {
        $id = $event->dataEvent['id'];
        $type = $event->dataEvent['type'];

        $instanceDB = DB::table(Str::plural($type))->where('id', $id);
        $itemDB = json_decode($instanceDB->get(), true)[0];
        if (!isset($itemDB['name'])) return false;

        $props = ReadingFileService::type_getPath('json', 'entities', $type, 'props.json');
        $valColNameHasFormula = Helper::getValColNamesValueNotEmpty($props, 'formula');

        foreach ($valColNameHasFormula as $value) {
            switch ($value) {
                case 'All_SlugifyByName':
                    $newValueArray = All_SlugifyByName::All_SlugifyByName($type, $itemDB);
                    $instanceDB->update($newValueArray);
                    break;

                case 'All_ConcatNameWith123':
                    $concatStr = 123;
                    $props = Helper::getColNamesValueNotEmpty($props, 'formula');
                    $colNameHasFormula = array_filter($props, fn ($item) => $item['formula'] === $value);
                    $newValueArray = All_ConcatNameWith123::All_ConcatNameWith123($colNameHasFormula, $concatStr, $itemDB);
                    $instanceDB->update($newValueArray);
                    break;
            }
        }
    }
}
