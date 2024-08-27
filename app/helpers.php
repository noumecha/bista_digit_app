<?php

use Illuminate\Support\Facades\Route;

    if(!function_exists('is_current_route')) {
        /**
         * check if current route matches the given route name
         * @param string $routeName
         * @return bool
         */
        function is_current_route($routeName) {
            return Route::currentRouteName() === $routeName;
        }
    }
