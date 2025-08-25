<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        // 
        View::composer('*', function ($view) {
            // Replace dots with hyphens in the view name for use in classes or IDs
            $viewName = str_replace('.', '-', $view->getName());
            View::share('view_name', $viewName);
        });        
    }
}
