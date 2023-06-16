<?php

namespace App\Listeners;

use App\Events\InspChklstEvent;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\User;
use App\Notifications\InspChklstNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class InspChklstListener
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
     * @param  \App\Events\InspChklstEvent  $event
     * @return void
     */
    public function handle(InspChklstEvent $event)
    {
        $data = $event->{'data'};
        $inspChklstShtId = $event->{'id'};
        $type = $event->{'type'};
        $ownerId = $data['owner_id'];
        $dataNoOrFail = $data['no_or_fail'] ?? [];
        $dataComment = $data['comment'] ?? [];
        $typePlural = Str::plural($type);
        $routeName = "{$typePlural}.edit";
        $routeExits = (Route::has($routeName));
        $url =  $routeExits ? route($routeName, $inspChklstShtId) : "#";
        $inspChklstSht = Qaqc_insp_chklst_sht::findOrFail($inspChklstShtId);
        $internalIds = $inspChklstSht->getLines->where('control_type_id', 7)->pluck('owner_id');
        $users = User::whereIn('id', $internalIds)->get();
        $owner = User::findFromCache($ownerId);
        $monitors = $inspChklstSht->getTmplSheet->getMonitors1()->pluck('email')->toArray();
        Notification::send($users, new InspChklstNotification(
            $owner,
            $dataNoOrFail,
            $dataComment,
            $inspChklstShtId,
            $url,
            $monitors
        ));
    }
}
