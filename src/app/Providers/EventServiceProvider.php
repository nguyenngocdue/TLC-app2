<?php

namespace App\Providers;

use App\Events\CreatedDocumentEvent;
use App\Events\RequestSignOffEvent;

use App\Events\UpdatedDocumentEvent;
use App\Events\UpdatedEsgSheetEvent;
use App\Events\UpdatedProdSequenceEvent;
use App\Events\UpdatedQaqcChklstEvent;
use App\Events\UpdatedQaqcChklstSheetEvent;
use App\Events\UpdatedSubProjectEvent;
use App\Events\UserSignedInEvent;
//------------
use App\Listeners\CreatedDocumentListener;
use App\Listeners\RequestSignOffListener;
use App\Listeners\UpdatedDocumentListener;
use App\Listeners\UpdatedEsgSheetListener;
use App\Listeners\UpdatedProdSequenceListener;
use App\Listeners\UpdatedQaqcChklstListener;
use App\Listeners\UpdatedQaqcChklstSheetListener;
use App\Listeners\UpdatedSubProjectListener;
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

        //Create and Update generic documents
        CreatedDocumentEvent::class => [CreatedDocumentListener::class],
        UpdatedDocumentEvent::class => [UpdatedDocumentListener::class,],

        //Update specific entities
        UpdatedSubProjectEvent::class => [UpdatedSubProjectListener::class],
        UpdatedQaqcChklstSheetEvent::class => [UpdatedQaqcChklstSheetListener::class],
        UpdatedQaqcChklstEvent::class => [UpdatedQaqcChklstListener::class],
        UpdatedProdSequenceEvent::class => [UpdatedProdSequenceListener::class],
        UpdatedEsgSheetEvent::class => [UpdatedEsgSheetListener::class],

        //Specific actions
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
