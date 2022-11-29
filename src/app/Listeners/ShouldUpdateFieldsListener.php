<?php

namespace App\Listeners;

use App\Events\EntityCreatedEvent;
use App\Http\Services\ReadingFileService;
use App\View\Components\Formula\All_ConcatNameWith123;
use App\View\Components\Formula\All_SlugifyByName;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShouldUpdateFieldsListener implements ShouldQueue
{
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
        $itemDB['name'] = $itemDB['name'] ?? "";

        $props = ReadingFileService::type_getPath('json', 'entities', $type, 'props.json');

        foreach ($props as $prop) {
            if ($prop['formula'] === '') continue;
            switch ($prop['formula']) {
                case "All_ConcatNameWith123":
                    $value = (new All_ConcatNameWith123())($itemDB['name']);
                    break;
                case "All_SlugifyByName":
                    $name = $itemDB['slug'] ?? $itemDB['name'];
                    $value = (new All_SlugifyByName())($name, $type, $itemDB['id']);
                    break;
                default:
                    break;
            }
            $instanceDB->update([$prop['column_name'] => $value]);
        }
    }
}
