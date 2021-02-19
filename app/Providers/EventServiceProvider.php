<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // add your listeners (aka providers) here
            'SocialiteProviders\\Slack\\SlackExtendSocialite@handle',
        ],
        \BeyondCode\LaravelWebSockets\Events\UnsubscribedFromChannel::class => [
            App\Listeners\PresenceChannelUnsubscribed::class,
        ],
        \BeyondCode\LaravelWebSockets\Events\SubscribedToChannel::class => [
            App\Listeners\PresenceChannelSubscribed::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
