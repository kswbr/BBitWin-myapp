<?php

namespace App\Socialite\Line;

use Laravel\Socialite\SocialiteServiceProvider;

class LineServiceProvider extends SocialiteServiceProvider
{
    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot() {
        \Socialite::extend('line', function ($app) {
            $config = $this->app['config']['services.line'];
            return \Socialite::buildProvider(LineProvider::class, $config);
        });
    }
}
