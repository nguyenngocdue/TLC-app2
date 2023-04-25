<?php

namespace App\Providers;

use App\Events\BroadcastEvents\BroadcastRemindSignOffEvent;
use App\Events\CreateNewDocumentEvent;
use App\Events\EntityCreatedEvent;
use App\Events\EntityUpdatedEvent;
use App\Events\SendEmailItemCreated;
use App\Events\SendMailForInspector;
use App\Events\UpdateChklstProgressEvent;
use App\Events\UpdatedDocumentEvent;
use App\Events\UpdateStatusChklstRunEvent;
use App\Listeners\RemindSignOffListener;
use App\Listeners\SendCreateNewDocumentNotificationListener;
use App\Listeners\SendEmailListener;
use App\Listeners\SendMailForInspectorListener;
use App\Listeners\SendUpdatedDocumentNotificationListener;
use App\Listeners\ShouldUpdateFieldsListener;
use App\Listeners\UpdateChklstProgressFromSheetListener;
use App\Listeners\UpdatedChklstSheetProgressListener;
use App\Listeners\UpdateStatusChklstRunListener;
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
            UpdatedChklstSheetProgressListener::class
        ],
        UpdateChklstProgressEvent::class => [UpdateChklstProgressFromSheetListener::class],

        CreateNewDocumentEvent::class => [SendCreateNewDocumentNotificationListener::class],
        SendMailForInspector::class => [SendMailForInspectorListener::class],

        BroadcastRemindSignOffEvent::class => [RemindSignOffListener::class],

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
