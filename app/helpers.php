<?php

use App\Models\AnneeScolaire;
use App\Models\CoefAnneeScolaire;
use App\Models\Coefficient;
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

    /**
     * function to determine reussite percent
     */
    function getWinPercent($datas) {
        $percent = 0;
        $totalData = count($datas);
        $totalWinData = 0;
        foreach ($datas as $data) {
            if ($data >= 10) {
                $totalWinData += 1;
            }
        }
        $percent = ($totalWinData / $totalData) * 100;
        return $percent;
    }

    /**
     * function to determine the number of data higher than 10
     */
    function getNbWin($datas) {
        $nbWin = 0;
        foreach ($datas as $data) {
            if($data >= 10) {
                $nbWin += 1;
            }
        }
        return $nbWin;
    }

    /**
     * function to calculate total Note x Coef
     */
    function totalNoteCoef($coefs, $notes) {
        $totalNoteCoef = 0;
        foreach ($notes as $note) {
            foreach ($coefs as $coef) {
                $totalNoteCoef += ($coef * $note);
            }
        }
        return $totalNoteCoef;
    }

    /**
     * function to determine the total of coefs
     */
    function totalCoefs ($coefs) {
        $totalCoefs = 0;
        foreach ($coefs as $coef) {
            $totalCoefs += $coef;
        }
        return $totalCoefs;
    }

    /**
     * function to determine average
     */
    function getAverage($coefs, $notes) {
        $totalNoteCoefs = totalNoteCoef($coefs, $notes);
        $totalCoefs = totalCoefs($coefs);
        $avg = $totalNoteCoefs / $totalCoefs;
        return $avg;
    }

    /**
     * function to determine standard deviation of moyennes
     */
    function getStandardDeviation($notes) {
        $avg = getGeneralMoy($notes);
        $sumNoteMinusAvg = 0;
        foreach ($notes as $note) {
            $sumNoteMinusAvg += pow(($note - $avg), 2);
        }
        $sd = $sumNoteMinusAvg / count($notes);
        return $sd;
    }

    /**
     * function create appreciation
     */
    function getAppreciation($moy) {
       $appreciationText = '';
        if (($moy) < 10) {
            $appreciationText = "D (CNA)";
        } else if (($moy) >= 10 && ($moy) < 12) {
            $appreciationText = "CMA (C)";
        } else if (($moy) >= 12 && ($moy) < 14) {
            $appreciationText = "CA (C+)";
        } else if (($moy) >= 14 && ($moy) < 15) {
            $appreciationText = "CBA (B)";
        } else if (($moy) >= 15 && ($moy) < 16) {
            $appreciationText = "CBA (B+)";
        } else if (($moy) >= 16 && ($moy) < 18) {
            $appreciationText = "CTBA (A)";
        } else if (($moy) >= 18 && ($moy) <= 20) {
            $appreciationText = "CTBA (A+)";
        } else {
            $appreciationText = "NOTE INVALIDE";
        }

        return $appreciationText;
    }

    /**
     * function to return matieres ids base on group
     */
    function getGroupeMatieresIds($group, $yearId) {
        $coefsAnneeScolairesFistGroup = CoefAnneeScolaire::all()->where('annee_scolaire_id',$yearId)
            ->where('groupe_matiere',$group)
            ->pluck('coefficient_id');
        $coefficientsGroupIds = Coefficient::all()->whereIn('id', $coefsAnneeScolairesFistGroup)->pluck('matiere_id');
        return $coefficientsGroupIds;
    }

    /**
     * function that return configuration_matiere of a current year - pluck by matiere_id
     */
    function getCurrentYearCoefConfigurationMatId($yearId) {
        $coefsAnneeScolaires = CoefAnneeScolaire::all()->where('annee_scolaire_id', $yearId)->pluck('coefficient_id');
        $coefficients = Coefficient::all()->whereIn('id', $coefsAnneeScolaires)->pluck('matiere_id');
        return $coefficients;
    }