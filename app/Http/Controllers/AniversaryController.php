<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Carbon\Carbon;

class AniversaryController extends Controller
{
    public function birthday()
    {
        $monthBirthday = '';
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $date = $date->format('m');
        $employees = [];
        foreach (Employee::all() as $employee) {
            if ($employee->user->status) {
                if ($employee->birthday_date != null) {
                    $birthday = explode('-', $employee->birthday_date);
                    $monthAniversaryth = $birthday[1];
                    if ($monthAniversaryth == $date) {
                        array_push($employees, $employee);
                    }
                }
            }
        }
        return view('aniversary.birthday', compact('employees', 'date'));
    }

    public function aniversary()
    {
        $monthAniversarythAniversary = '';
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $date = $date->format('m');
        $employees = [];
        foreach (Employee::all() as $employee) {
            if ($employee->user->status) {
                if ($employee->date_admission != null) {
                    $birthday = explode('-', $employee->date_admission);
                    $monthAniversaryth = $birthday[1];
                    if ($monthAniversaryth == $date) {
                        $dateAdmission = Carbon::parse($employee->date_admission);
                        $yearsWork = $dateAdmission->diffInYears($carbon->now()->addMonth());
                        if ($yearsWork > 0) {
                            array_push($employees, $employee);
                        }
                    }
                }
            }
        }
        $lastDayofMonth = \Carbon\Carbon::now()->endOfMonth();
        return view('aniversary.aniversary', compact('employees', 'lastDayofMonth'));
    }
}
