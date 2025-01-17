<?php

namespace App\Providers;

use App\Events\CleanUpTrashEvent;
use App\Events\CreatedDocumentEvent2;
use App\Events\InspectionSignoff\SignOffRecallEvent;
use App\Events\InspectionSignoff\SignOffRemindEvent;
use App\Events\InspectionSignoff\SignOffRequestEvent;
use App\Events\InspectionSignoff\SignOffSubmittedEvent;
use App\Events\OpenedDocumentEvent;
use App\Events\Production\UpdateRoutingAvgActualHourEvent;
use App\Events\StaffTimesheet\EndOfWeekRemindEvent;
use App\Events\StaffTimesheet\StartOfWeekRemindEvent;
use App\Events\TransferDiginetDataEvent;
use App\Events\UpdatedDocumentEvent;
use App\Events\UpdatedEsgSheetEvent;
use App\Events\UpdatedProdSequenceEvent;
use App\Events\UpdatedQaqcChklstEvent;
use App\Events\UpdatedQaqcChklstSheetEvent;
use App\Events\UpdatedUserPositionEvent;
use App\Events\UserSignedInEvent;
//------------
use App\Listeners\CleanUpTrashListener;
use App\Listeners\CreatedDocumentListener2;
use App\Listeners\Diginet\TransferDiginetDataListener;
use App\Listeners\InspectionSignoff\SignOffRecallListener;
use App\Listeners\InspectionSignoff\SignOffRemindListener;
use App\Listeners\InspectionSignoff\SignOffRequestListener;
use App\Listeners\InspectionSignoff\SignOffSubmittedListener;
use App\Listeners\OpenedDocumentListener;
use App\Listeners\Production\UpdateRoutingAvgActualHourListener;
use App\Listeners\StaffTimesheet\EndOfWeekRemindListener;
use App\Listeners\StaffTimesheet\StartOfWeekRemindListener;
use App\Listeners\UpdatedDocumentListener2;
use App\Listeners\UpdatedEsgSheetListener;
use App\Listeners\UpdatedProdSequenceListener;
use App\Listeners\UpdatedQaqcChklstListener;
use App\Listeners\UpdatedQaqcChklstSheetListener;
use App\Listeners\UpdatedUserPositionListener;
use App\Listeners\UserSignedInListener;
//------------
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        //Auth
        Registered::class => [SendEmailVerificationNotification::class],
        UserSignedInEvent::class => [UserSignedInListener::class],

        //Open generic documents
        OpenedDocumentEvent::class => [OpenedDocumentListener::class],

        //Create and Update generic documents
        CreatedDocumentEvent2::class => [CreatedDocumentListener2::class],
        UpdatedDocumentEvent::class => [UpdatedDocumentListener2::class,],

        //Update specific entities
        //This is to update progress of each checksheet
        UpdatedQaqcChklstSheetEvent::class => [UpdatedQaqcChklstSheetListener::class],
        //This is to update dropdown for 3rd party dashboard options
        UpdatedQaqcChklstEvent::class => [UpdatedQaqcChklstListener::class],

        UpdatedProdSequenceEvent::class => [UpdatedProdSequenceListener::class],
        UpdatedEsgSheetEvent::class => [UpdatedEsgSheetListener::class],

        //Specific actions
        SignOffRequestEvent::class => [SignOffRequestListener::class],
        SignOffRecallEvent::class => [SignOffRecallListener::class],
        SignOffSubmittedEvent::class => [SignOffSubmittedListener::class],

        //Schedule
        SignOffRemindEvent::class => [SignOffRemindListener::class],

        //STS
        StartOfWeekRemindEvent::class => [StartOfWeekRemindListener::class],
        EndOfWeekRemindEvent::class => [EndOfWeekRemindListener::class],

        //Handle update job description
        UpdatedUserPositionEvent::class => [UpdatedUserPositionListener::class],

        //Diginet
        TransferDiginetDataEvent::class => [TransferDiginetDataListener::class],

        //Clean up trash
        CleanUpTrashEvent::class => [CleanUpTrashListener::class],

        //Production 
        UpdateRoutingAvgActualHourEvent::class => [UpdateRoutingAvgActualHourListener::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
