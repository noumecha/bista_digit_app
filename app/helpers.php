<?php

use App\Models\AnneeScolaire;
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

    /**
     * this function is for get the current year
     */
    function getCurrentYear() {
        $currentYear = AnneeScolaire::all()->where('statut','=', true)->first();

        return $currentYear;
    }

    /**
     * for formating date in the blade template
     */
    function formatDate($date = '', $format = 'Y-m-d') {
        if ($date == '' || $date == null) {
            return;
        }
        return date($format, strtotime($date));
    }

    /**
     * function to determine range of an element in array
     */
    function getRange($el, $table) {
        $r = 1;
        foreach ($table as $t) {
            if($t > $el) {
                $r++;
            }
        }
        return $r;
    }

    /**
     * function to determine the general average of an array
     */
    function getGeneralMoy($datas) {
        $s = 0;
        foreach ($datas as $data) {
            $s += $data;
        }
        $gcma = $s/count($datas);
        return $gcma;
    }