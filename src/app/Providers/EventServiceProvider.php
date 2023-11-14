<?php

namespace App\Providers;

use App\Events\CreateNewDocumentEvent;
use App\Events\EntityCreatedEvent;
use App\Events\EntityUpdatedEvent;
use App\Events\InspChklstEvent;
use App\Events\LoggedUserSignInHistoriesEvent;
use App\Events\RequestSignOffEvent;
use App\Events\SendEmailItemCreated;
use App\Events\SendMailForInspector;
use App\Events\UpdateChklstProgressEvent;
use App\Events\UpdatedDocumentEvent;
use App\Events\UpdatedEsgSheetEvent;
use App\Events\UpdatedProdSequenceEvent;
use App\Events\UpdateStatusChklstRunEvent;
//------------
use App\Listeners\InspChklstListener;
use App\Listeners\LoggedUserSignInHistoriesListener;
use App\Listeners\RequestSignOffListener;
use App\Listeners\SendCreateNewDocumentNotificationListener;
use App\Listeners\SendEmailListener;
use App\Listeners\SendMailForInspectorListener;
use App\Listeners\SendUpdatedDocumentNotificationListener;
use App\Listeners\ShouldUpdateFieldsListener;
use App\Listeners\UpdateChklstProgressFromSheetListener;
use App\Listeners\UpdateChklstSheetProgressListener;
use App\Listeners\UpdatedEsgSheetListener;
use App\Listeners\UpdatedProdSequenceListener;
use App\Listeners\UpdateStatusChklstRunListener;
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
        Registered::class => [SendEmailVerificationNotification::class],
        EntityCreatedEvent::class => [ShouldUpdateFieldsListener::class],
        EntityUpdatedEvent::class => [ShouldUpdateFieldsListener::class],
        SendEmailItemCreated::class => [SendEmailListener::class],
        UpdateStatusChklstRunEvent::class => [UpdateStatusChklstRunListener::class],
        UpdatedDocumentEvent::class => [
            SendUpdatedDocumentNotificationListener::class,
            UpdateChklstSheetProgressListener::class
        ],
        UpdateChklstProgressEvent::class => [UpdateChklstProgressFromSheetListener::class],
        CreateNewDocumentEvent::class => [SendCreateNewDocumentNotificationListener::class],
        SendMailForInspector::class => [SendMailForInspectorListener::class],
        // BroadcastRemindSignOffEvent::class => [RemindSignOffListener::class],
        InspChklstEvent::class => [InspChklstListener::class],
        LoggedUserSignInHistoriesEvent::class => [LoggedUserSignInHistoriesListener::class],

        UpdatedProdSequenceEvent::class => [UpdatedProdSequenceListener::class],
        UpdatedEsgSheetEvent::class => [UpdatedEsgSheetListener::class],

        // SendEmailRequestSignOffEvent::class => [SendEmailRequestSignOffListener::class],

        RequestSignOffEvent::class => [RequestSignOffListener::class],
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
