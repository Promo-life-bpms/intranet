<?php

namespace App\Http\Controllers;

use App\Models\Communique;
use App\Models\Employee;
use App\Models\Events;
use App\Models\NoWorkingDays;
use App\Models\Publications;
use App\Models\Request as ModelsRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __invoke()
    {
        self::validateCommunicated();
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
            ->select('requests.id')
            ->get();
        foreach ($vacations as $vacation) {
            $vacation = ModelsRequest::find($vacation->id);
            array_push($empleadosAusentes, $vacation->employee->user);
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

        $publications = Publications::orderBy('created_at', 'desc')->paginate(10);

        return view('home.index', compact('proximasVacaciones', 'employeesBirthday', 'employeesAniversary', 'noworkingdays', 'eventos', 'communiquesImage', 'monthEmployeeController', 'publications', 'date', 'empleadosAusentes'));
    }


    public function getCommunique($id)
    {
        $comunique = Communique::all()->where('id', $id)->toArray();
        return array_values($comunique);
    }

    public function validateCommunicated()
    {
        $communiques =  Communique::all();

        foreach ($communiques as  $communique) {
            $day = $communique->created_at->format('Y-m-d');

            $expiration = Carbon::parse($day)->addDays(5);
            $expirationFormat = $expiration->format('Y-m-d');

            $today = Carbon::now();
            $todayFormat = $today->format('Y-m-d');

            if ($todayFormat >= $expirationFormat) {
                $communique->delete();
            }
        }
    }
}
