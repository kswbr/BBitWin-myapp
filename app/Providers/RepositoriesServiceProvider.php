<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        \App::bind('App\Repositories\CampaignRepositoryInterface', 'App\Repositories\Eloquent\CampaignRepository');
        \App::bind('App\Repositories\LotteryRepositoryInterface', 'App\Repositories\Eloquent\LotteryRepository');
        \App::bind('App\Repositories\EntryRepositoryInterface', 'App\Repositories\Eloquent\EntryRepository');
    }
}
