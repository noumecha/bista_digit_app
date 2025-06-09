<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('access-student', fn($user) =>
            $user->isStudent() || $user->isAdmin()
        );

        Gate::define('access-teacher', fn($user) =>
            $user->isTeacher() || $user->isAdmin()
        );

        Gate::define('access-admin', fn($user) =>
            $user->isAdmin()
        );

        Gate::define('access-discipline', fn($user) =>
            $user->isDisciplineMaster() || $user->isAdmin()
        );
    }
}
