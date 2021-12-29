<?php

namespace App\Http\Controllers;

use App\Models\Communique;
use App\Models\Employee;
use App\Models\NoWorkingDays;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {

        $monthBirthday='';
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $date = $date->format('m');
        $employees = [];
        foreach (Employee::all() as $employee) {
            if ($employee->birthday_date != null) {
                $birthday = explode('-', $employee->birthday_date);
                $monthAniversaryth = $birthday[1];
                if ($monthAniversaryth == $date) {
                    array_push($employees, $employee);
                }
            }
        }

        $monthAniversarythAniversary='';
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $date = $date->format('m'); 
        $employees = [];
        foreach (Employee::all() as $employee) {
            if ($employee->date_admission != null) {
                $birthday = explode('-', $employee->date_admission);
                $monthAniversaryth = $birthday[1];
                if ($monthAniversaryth == $date) {
                    array_push($employees, $employee);
                }
            }
        }

        
        if($date==1){
            $monthBirthday = 'Enero';
            $monthAniversary = 'Enero';
        }elseif($date==2){
            $monthBirthday = 'Febrero';
            $monthAniversary = 'Febrero';
        }elseif($date==3){
            $monthBirthday = 'Marzo';
            $monthAniversary = 'Marzo';
        }elseif($date==4){
            $monthBirthday = 'Abril';
            $monthAniversary = 'Abril';
        }elseif($date==5){
            $monthBirthday = 'Mayo';
            $monthAniversary = 'Mayo';
        }elseif($date==6){
            $monthBirthday = 'Junio';
            $monthAniversary = 'Junio';
        }elseif($date==7){
            $monthBirthday = 'Julio';
            $monthAniversary = 'Julio';
        }elseif($date==8){
            $monthBirthday = 'Agosto';
            $monthAniversary = 'Agosto';
        }elseif($date==9){
            $monthBirthday = 'Septiembre';
            $monthAniversary = 'Septiembre';
        }elseif($date==10){
            $monthBirthday = 'Octubre';
            $monthAniversary = 'Octubre';
        }elseif($date==11){
            $monthBirthday = 'Noviembre';
            $monthAniversary = 'Noviembre';
        }elseif($date==12){
            $monthBirthday = 'Diciembre';
            $monthAniversary = 'Diciembre';
        }else{
            $monthBirthday = 'Desconocido';
            $monthAniversary = 'Enero';
        }

        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();
        $communiques = Communique::paginate(3);
        return view('home.index', compact('communiques','employees','monthBirthday','monthAniversary','noworkingdays'));
    }
}
