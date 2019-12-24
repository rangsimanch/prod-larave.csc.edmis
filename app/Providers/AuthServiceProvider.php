<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

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

        if (!app()->runningInConsole()) {
            Passport::routes();
        };

<<<<<<< HEAD
<<<<<<< HEAD
        // Auth gates for: construction_contract_select
        Gate::define('construction_contract_selects', function ($user) {
            return !$user->getIsAdminAttribute() && ($user->construction_contracts->count() > 1);
=======
        // Auth gates for: TeamSelect
        Gate::define('construction_contract_select', function ($user) {
            return !$user->isAdmin() && ($user->construction_contracts->count() > 1);
>>>>>>> parent of 2c893c1... Constraction Contract Select
=======
        // Auth gates for: TeamSelect
        Gate::define('construction_contract_select', function ($user) {
            return !$user->isAdmin() && ($user->construction_contracts->count() > 1);
>>>>>>> parent of 2c893c1... Constraction Contract Select
        });

        Gate::define('assign_item_to_member', function ($user) {
            return $user->isAdmin() || $user->isTeamAdmin();
        });
    }
}
