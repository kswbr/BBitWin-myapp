<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Services\ProjectService;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::tokensCan([
            'check-admin' => 'check admin',
            'instant-win' => 'instant win',
            'vote-event' => 'vote event',
            'retry' => 'instant win retry challenge',
            'winner' => 'instant win winner',
            'form' => 'instantwin form token',
        ]);
        Passport::routes();

        $projectService = \App::make(ProjectService::class);
        $group_in_project = function($user) use ($projectService){
            return $user->project === $projectService->getCode() || (bool)$user->allow_over_project === true;
        };

        Gate::define('allow_campaign', function ($user) use ($group_in_project){
            return ((bool)$user->allow_campaign === true && $group_in_project($user));
        });

        Gate::define('allow_vote', function ($user) use ($group_in_project){
            return ((bool)$user->allow_vote === true && $group_in_project($user));
        });

        Gate::define('allow_user', function ($user) use ($group_in_project){
            return ((bool)$user->allow_user === true && $group_in_project($user));
        });

        Gate::define('allow_create_and_delete', function ($user) use ($group_in_project){
            return ((int)$user->role >= 1 && $group_in_project($user));
        });

     }
}
