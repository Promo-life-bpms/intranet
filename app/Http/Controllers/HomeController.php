<?php

namespace App\Http\Controllers;

use App\Models\Communique;
use App\Models\CommuniqueCompany;
use App\Models\CommuniqueDepartment;
use App\Models\Employee;
use App\Models\Events;
use App\Models\NoWorkingDays;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __invoke()
    {

        $monthBirthday = '';
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

        $monthAniversarythAniversary = '';
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


        if ($date == 1) {
            $monthBirthday = 'Enero';
            $monthAniversary = 'Enero';
        } elseif ($date == 2) {
            $monthBirthday = 'Febrero';
            $monthAniversary = 'Febrero';
        } elseif ($date == 3) {
            $monthBirthday = 'Marzo';
            $monthAniversary = 'Marzo';
        } elseif ($date == 4) {
            $monthBirthday = 'Abril';
            $monthAniversary = 'Abril';
        } elseif ($date == 5) {
            $monthBirthday = 'Mayo';
            $monthAniversary = 'Mayo';
        } elseif ($date == 6) {
            $monthBirthday = 'Junio';
            $monthAniversary = 'Junio';
        } elseif ($date == 7) {
            $monthBirthday = 'Julio';
            $monthAniversary = 'Julio';
        } elseif ($date == 8) {
            $monthBirthday = 'Agosto';
            $monthAniversary = 'Agosto';
        } elseif ($date == 9) {
            $monthBirthday = 'Septiembre';
            $monthAniversary = 'Septiembre';
        } elseif ($date == 10) {
            $monthBirthday = 'Octubre';
            $monthAniversary = 'Octubre';
        } elseif ($date == 11) {
            $monthBirthday = 'Noviembre';
            $monthAniversary = 'Noviembre';
        } elseif ($date == 12) {
            $monthBirthday = 'Diciembre';
            $monthAniversary = 'Diciembre';
        } else {
            $monthBirthday = 'Desconocido';
            $monthAniversary = 'Enero';
        }

        $eventos = Events::all();

        $id = Auth::user()->id;
        $employeeID = DB::table('employees')->where('user_id', $id)->value('id');


        /* $companyEmployee = DB::table('company_employee')->where('employee_id', $employeeID)->value('company_id');
        $companyCom = CommuniqueCompany::all()->where('company_id', $companyEmployee)->pluck('communique_id', 'communique_id');
        $companyCommuniques = Communique::all()->whereIn('id', $companyCom);

        $employeePosition = DB::table('employees')->where('id', $employeeID)->value('position_id');
        $employeeDepartment = DB::table('positions')->where('id', $employeePosition)->value('department_id');
        $departmentCom = CommuniqueDepartment::all()->where('department_id', $employeeDepartment)->pluck('communique_id', 'communique_id');
        $departmentCommuniques = Communique::all()->whereIn('id', $departmentCom); */

        $communiquesImage = DB::table('communiques')->whereNotNull('image')->get();
        /* 

        if (count($communiquesImage) == 0) {
            
            $communiquesImage == null;
        }

        dd($communiquesImage); */

        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();

        return view('home.index', compact('employees', 'monthBirthday', 'monthAniversary', 'noworkingdays', 'eventos', 'communiquesImage'));
    }


    public function getCommunique($id)
    {
        $comunique = Communique::all()->where('id', $id)->toArray();
        return array_values($comunique);
    }
}
