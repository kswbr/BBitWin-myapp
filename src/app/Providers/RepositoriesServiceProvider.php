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
        \App::bind('App\Repositories\VoteRepositoryInterface', 'App\Repositories\Eloquent\VoteRepository');
        \App::bind('App\Repositories\PlayerRepositoryInterface', 'App\Repositories\Eloquent\PlayerRepository');
    }
}
