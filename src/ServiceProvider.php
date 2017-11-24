<?php

namespace NotificationChannels\FCM;

use Illuminate\Notifications\ChannelManager;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->make(ChannelManager::class)->extend('fcm', function () {
            return new FCMChannel($this->app->make('fcm.sender'), $this->app->make('events'));
        });
    }

    /**
     * Register any package services.
     */
    public function register()
    {
        $this->app->register(\LaravelFCM\FCMServiceProvider::class);
    }
}
