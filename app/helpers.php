<?php

use App\Models\AnneeScolaire;
use App\Models\Bulletin;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\CoefAnneeScolaire;
use App\Models\Coefficient;
use App\Models\EnseignantPrincipal;
use App\Models\Note;
use App\Models\User;
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
     * get school month for disciplines
     */
    function getAllSchoolMonths() {
        $schoolYear = getCurrentYear();
        $months = [];
        $yearStartDate = new DateTime($schoolYear->dateDeDebut);
        $yearEndDate = new DateTime($schoolYear->dateDeFin);
        $yearEndDate->modify("-1 month");
        $interval = new DateInterval('P1M'); // 1 month interval
        $datePeriod = new DatePeriod($yearStartDate, $interval, $yearEndDate);
        foreach ($datePeriod as $date) {
            array_push($months, $date);
        }
        return $months;
    }

    /**
     * get month name base on his english name
     */
    function monthNameToFrench($month) {
        $frenchNames = [
            "01" => "Janvier","02" => "Fevrier","03" => "Mars","04" => "Avril",
            "05" => "Mai","06" => "Juin","07" => "Juillet","08" => "Août",
            "09" => "Septembre","10" => "Octobre","11" => "Novembre","12" => "Decembre"
        ];
        return $frenchNames[$month];
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
    function getRange($data, $datas) {
        $range = 1;
        foreach ($datas as $n) {
            if($n > $data) {
                $range++;
            }
        }
        return $range;
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
     * function to automatically update notes
     */
    function updateNoteMinMaxRange($matiereId, $classeId, $evaluationId) {
        try {
            $classeYearStudentsIds = ClasseAnneeScolaireStudent::all()
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('classe_id',$classeId)
                ->pluck('user_id');
            $notes = Note::all()->where('matiere_id',$matiereId)
                ->where('evaluation_id',$evaluationId)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->whereIn('user_id', $classeYearStudentsIds);
            $noteValues = [];
            foreach($notes as $note) {
                array_push($noteValues, $note->note);
            }
            $minValue = min($noteValues);
            $maxValue = max($noteValues);
            $gcma = getGeneralMoy($noteValues);
            foreach($notes as $note) {
                $range = getRange($note->note, $noteValues);
                $note->update([
                    'range' => $range,
                    'min_value' => $minValue,
                    'max_value' => $maxValue,
                    'gcma' => $gcma,
                ]);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * update specific report card discipline
     */
    function updateStudentDisciplineOnReportCard($bulletin, $discipline) {
        return true;
    }
    /**
     * function that help to update specific report Card
     *
     */
    function updateSpecificReportCard($note) {
        try {
            $notes = Note::where('user_id', $note->user_id)
                    ->where('evaluation_id', $note->evaluation_id)
                    ->where('classe_id', $note->classe_id)
                    ->where('annee_scolaire_id', getCurrentYear()->id)
                    ->get();
            $average = getAverage(getCurrentYear()->id, $notes);
            $appreciation = getAppreciation($average);
            // update corresponding user bulletin
            $bulletin = Bulletin::where('user_id', $note->user_id)
                ->where('classe_id', $note->classe_id)
                ->where('evaluation_id', $note->evaluation_id)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('trimestre_id', $note->evaluation->trimestre_id)->first();
            $bulletin->update([
                'appreciation' => $appreciation,
                'average' => $average,
            ]);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    /**
     * function to update report card stats
     */
    function updateAllReportCardStats($classeId, $evaluationId, $trimestreId, $yearId) {
        try {
            $classeYearStudentsIds = ClasseAnneeScolaireStudent::all()
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('classe_id',$classeId)
                ->pluck('user_id');
            $bulletins = Bulletin::where('evaluation_id',$evaluationId)
                ->where('evaluation_id',$evaluationId)
                ->where('annee_scolaire_id',$yearId)
                ->whereIn('user_id', $classeYearStudentsIds)
                ->where('trimestre_id',$trimestreId)->get();
            $bulletinValues = [];
            foreach($bulletins as $bulletin) {
                array_push($bulletinValues, $bulletin->average);
            }
            $minValue = min($bulletinValues);
            $maxValue = max($bulletinValues);
            $gcma = getGeneralMoy($bulletinValues);
            $sd = getStandardDeviation($bulletinValues);
            foreach($bulletins as $bulletin) {
                $range = getRange($bulletin->average, $bulletinValues);
                $bulletin->update([
                    'min_average' => $minValue,
                    'max_average' => $maxValue,
                    'general_average' => $gcma,
                    'standard_deviation' => $sd,
                    'range' => $range,
                ]);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * update trimestrial report card stats
     */
    function updateAllTrimReportCardStats($classeId, $trimestreId, $typeBulltin, $yearId) {
        try {
            $bulletins = Bulletin::where('classe_id',$classeId)
                ->where('type_bulletin',$typeBulltin)
                ->where('annee_scolaire_id',$yearId)
                ->where('trimestre_id',$trimestreId)->get();
            $bulletinValues = [];
            foreach($bulletins as $bulletin) {
                array_push($bulletinValues, $bulletin->average);
            }
            $minValue = min($bulletinValues);
            $maxValue = max($bulletinValues);
            $gcma = getGeneralMoy($bulletinValues);
            $sd = getStandardDeviation($bulletinValues);
            foreach($bulletins as $bulletin) {
                $range = getRange($bulletin->average, $bulletinValues);
                $bulletin->update([
                    'min_average' => $minValue,
                    'max_average' => $maxValue,
                    'general_average' => $gcma,
                    'standard_deviation' => $sd,
                    'range' => $range,
                ]);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
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
     * function for calculate total of a coefs of matiere group
     */
    function totalCoefGroup($datas) {
        $total = 0;
        foreach($datas as $data) {
            $total += $data->matiere->getCoef($data->classe_id);
        }
        return $total;
    }

    /**
     * function for calculate total of a notes of matiere group
     */
    function totalNoteCoefGroup($datas) {
        $total = 0;
        foreach($datas as $data) {
            $total += ($data->note * $data->matiere->getCoef($data->classe_id));
        }
        return $total;
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
     * function to determine average
     */
    function getAverage($yearId, $notes) {
        $avg = 0;
        $totalNoteCoefs = 0;
        $coefs = [];
        foreach ($notes as $note) {
            $coef = Coefficient::all()->where('matiere_id', $note->matiere_id)
                ->where('classe_id', $note->classe->id)
                ->where('annee_scolaire_id', $yearId)->first();
            $coefValue = CoefAnneeScolaire::all()->where('coefficient_id', $coef->id)->first();
            $totalNoteCoefs += ($note->note * $coefValue->coefficient_value);
            array_push($coefs, $coefValue->coefficient_value);
        }
        $totalCoefs = totalCoefs($coefs);
        $avg = $totalNoteCoefs / $totalCoefs;
        return $avg;
    }

    /**
     * calculate trim average
     */
    function getTrimAverage($datas) {
        $avg = 0;
        $som = 0;
        $total = count($datas);
        foreach ($datas as $data) {
            $som += $data;
        }
        $avg = ($som / $total);
        return $avg;
    }
    /**
     * function to return matieres ids base on group name
     */
    function getGroupeMatieresIds($group, $yearId, $classeId) {
        $coefsAnneeScolairesFistGroup = CoefAnneeScolaire::all()->where('annee_scolaire_id',$yearId)
            ->where('groupe_matiere',$group)
            ->pluck('coefficient_id');
        $coefficientsGroupIds = Coefficient::all()->where('classe_id',$classeId)->whereIn('id', $coefsAnneeScolairesFistGroup)->pluck('matiere_id');
        return $coefficientsGroupIds;
    }

    /**
     * function that return configuration_matiere of a current year - pluck by matiere_id
     */
    function getCurrentYearCoefConfigurationMatId($yearId, $classeId) {
        $coefsAnneeScolaires = CoefAnneeScolaire::all()->where('annee_scolaire_id', $yearId)->pluck('coefficient_id');
        $coefficients = Coefficient::all()->where('classe_id', $classeId)->whereIn('id', $coefsAnneeScolaires)->pluck('matiere_id');
        return $coefficients;
    }

    /**
     * get the principal teacher base on the classe id
     */
    function getPrincipalClassTeacher($id, $yearId) {
        $teacherId = EnseignantPrincipal::all()
            ->where('classe_id', $id)
            ->where('annee_scolaire_id', $yearId)
            ->pluck('user_id')->first();
        $teacher = User::where('id', $teacherId)->first();
        $name = "";
        $teacher->sex->value === "F" ? $name = "Mme ".$teacher->name : $name = "M. ".$teacher->name;
        return $name;
    }

    /**
     * student displinces stats
     */
    function getDisciplinesStats($datas) {
        $absJust = 0;
        $absNonJust = 0;
        foreach($datas as $data) {
            $absJust += $data->heures_justifiees;
            $absNonJust += $data->total_absences;
        }
        $stats = [
            "absJust" => $absJust,
            "absNonJust" => $absNonJust
        ];
        return $stats;
    }