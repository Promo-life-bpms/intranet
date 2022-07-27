<?php

namespace App\Http\Controllers;

use App\Models\Communique;
use App\Models\Employee;
use App\Models\Events;
use App\Models\NoWorkingDays;
use App\Models\Publications;
use App\Models\Request as ModelsRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __invoke()
    {
        $monthBirthday = '';
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $date = $date->format('m');
        $employeesBirthday = [];
        foreach (Employee::all() as $employee) {
            if ($employee->birthday_date != null) {
                $birthday = explode('-', $employee->birthday_date);
                $monthAniversaryth = $birthday[1];
                if ($monthAniversaryth == $date) {
                    array_push($employeesBirthday, $employee);
                }
            }
        }

        $monthAniversarythAniversary = '';
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $date = $date->format('m');
        $employeesAniversary = [];
        foreach (Employee::all() as $employee) {
            if ($employee->date_admission != null) {
                $birthday = explode('-', $employee->date_admission);
                $monthAniversaryth = $birthday[1];
                if ($monthAniversaryth == $date) {
                    $dateAdmission = Carbon::parse($employee->date_admission);
                    $yearsWork = $dateAdmission->diffInYears($carbon->now()->addMonth());
                    if ($yearsWork > 0) {
                        array_push($employeesAniversary, $employee);
                    }
                }
            }
        }

        $empleadosAusentes = [];
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now()->format('Y-m-d');
        $vacations = DB::table('requests')
            ->join('request_calendars', 'requests.id', '=', 'request_calendars.requests_id')
            ->where('request_calendars.start', $date)
            ->where('requests.human_resources_status', 'Aprobada')
            ->select('requests.id', 'requests.reveal_id')
            ->get();
        foreach ($vacations as $vacation) {
            $vacation = ModelsRequest::find($vacation->id);
            $user = $vacation->employee->user;
            $user->reveal = '';
            if ($vacation->reveal_id) {
                $user->reveal =  User::find($vacation->reveal_id)->name;
            }
            array_push($empleadosAusentes, $user);
        }

        $proximasVacaciones = [];
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now()->format('Y-m-d');
        $vacations = DB::table('requests')
            ->join('request_calendars', 'requests.id', '=', 'request_calendars.requests_id')
            ->where('request_calendars.start', '>', $date)
            ->where('requests.human_resources_status', 'Aprobada')
            ->select('requests.id')
            ->groupBy('requests.id')
            ->get();

        foreach ($vacations as $vacation) {
            $vacation = ModelsRequest::find($vacation->id);
            array_push($proximasVacaciones, $vacation);
        }

        $toDay = $carbon->now();
        $date = $toDay->format('Y');

        $eventos = Events::all();

        $communiquesImage = DB::table('communiques')->whereNotNull('image')->get();

        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();

        $monthEmployeeController = MonthController::getEmpoyeeMonth();

        $publications = Publications::orderBy('created_at', 'desc')->simplePaginate(10); //get first 10 rows

        return view('home.index', compact('proximasVacaciones', 'employeesBirthday', 'employeesAniversary', 'noworkingdays', 'eventos', 'communiquesImage', 'monthEmployeeController', 'publications', 'date', 'empleadosAusentes'));
    }


    public function getCommunique($id)
    {
        $comunique = Communique::all()->where('id', $id)->toArray();
        return array_values($comunique);
    }
}
