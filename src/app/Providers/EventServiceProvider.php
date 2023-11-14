<?php

namespace App\Providers;

use App\Events\CreateNewDocumentEvent;
use App\Events\InspChklstEvent;
use App\Events\LoggedUserSignInHistoriesEvent;
use App\Events\RequestSignOffEvent;


use App\Events\UpdatedDocumentEvent;
use App\Events\UpdatedEsgSheetEvent;
use App\Events\UpdatedProdSequenceEvent;
use App\Events\UpdatedQaqcChklstEvent;
use App\Events\UpdatedQaqcChklstSheetEvent;
//------------
use App\Listeners\InspChklstListener;
use App\Listeners\LoggedUserSignInHistoriesListener;
use App\Listeners\RequestSignOffListener;
use App\Listeners\SendCreateNewDocumentNotificationListener;
use App\Listeners\SendUpdatedDocumentNotificationListener;
use App\Listeners\UpdateChklstProgressFromSheetListener;
use App\Listeners\UpdateChklstSheetProgressListener;
use App\Listeners\UpdatedEsgSheetListener;
use App\Listeners\UpdatedProdSequenceListener;
use App\Listeners\UpdatedQaqcChklstListener;
use App\Listeners\UpdatedQaqcChklstSheetListener;
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
        LoggedUserSignInHistoriesEvent::class => [LoggedUserSignInHistoriesListener::class],
        // EntityCreatedEvent::class => [ShouldUpdateFieldsListener::class],
        // EntityUpdatedEvent::class => [ShouldUpdateFieldsListener::class],
        // SendEmailItemCreated::class => [SendEmailListener::class],
        // UpdateStatusChklstRunEvent::class => [UpdateStatusChklstRunListener::class],
        // SendMailForInspector::class => [SendMailForInspectorListener::class],
        // SendEmailRequestSignOffEvent::class => [SendEmailRequestSignOffListener::class],

        // ????????????????
        UpdatedDocumentEvent::class => [SendUpdatedDocumentNotificationListener::class,],
        CreateNewDocumentEvent::class => [SendCreateNewDocumentNotificationListener::class],
        InspChklstEvent::class => [InspChklstListener::class],
        // ????????????????

        UpdatedQaqcChklstSheetEvent::class => [UpdatedQaqcChklstSheetListener::class],
        UpdatedQaqcChklstEvent::class => [UpdatedQaqcChklstListener::class],
        UpdatedProdSequenceEvent::class => [UpdatedProdSequenceListener::class],
        UpdatedEsgSheetEvent::class => [UpdatedEsgSheetListener::class],

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
