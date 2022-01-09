<?php

namespace App\Http\Controllers;

use App\Models\Employee;

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
            if ($employee->birthday_date != null) {
                $birthday = explode('-', $employee->birthday_date);
                $monthAniversaryth = $birthday[1];
                if ($monthAniversaryth == $date) {
                    array_push($employees, $employee);
                }
            }
        }

        if ($date == 1) {
            $monthBirthday = 'Enero';
        } elseif ($date == 2) {
            $monthBirthday = 'Febrero';
        } elseif ($date == 3) {
            $monthBirthday = 'Marzo';
        } elseif ($date == 4) {
            $monthBirthday = 'Abril';
        } elseif ($date == 5) {
            $monthBirthday = 'Mayo';
        } elseif ($date == 6) {
            $monthBirthday = 'Junio';
        } elseif ($date == 7) {
            $monthBirthday = 'Julio';
        } elseif ($date == 8) {
            $monthBirthday = 'Agosto';
        } elseif ($date == 9) {
            $monthBirthday = 'Septiembre';
        } elseif ($date == 10) {
            $monthBirthday = 'Octubre';
        } elseif ($date == 11) {
            $monthBirthday = 'Noviembre';
        } elseif ($date == 12) {
            $monthBirthday = 'Diciembre';
        } else {
            $monthBirthday = 'Desconocido';
        }

        return view('aniversary.birthday', compact('employees', 'monthBirthday'));
    }

    public function aniversary()
    {
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
            $monthAniversary = 'Enero';
        } elseif ($date == 2) {
            $monthAniversary = 'Febrero';
        } elseif ($date == 3) {
            $monthAniversary = 'Marzo';
        } elseif ($date == 4) {
            $monthAniversary = 'Abril';
        } elseif ($date == 5) {
            $monthAniversary = 'Mayo';
        } elseif ($date == 6) {
            $monthAniversary = 'Junio';
        } elseif ($date == 7) {
            $monthAniversary = 'Julio';
        } elseif ($date == 8) {
            $monthAniversary = 'Agosto';
        } elseif ($date == 9) {
            $monthAniversary = 'Septiembre';
        } elseif ($date == 10) {
            $monthAniversary = 'Octubre';
        } elseif ($date == 11) {
            $monthAniversary = 'Noviembre';
        } elseif ($date == 12) {
            $monthAniversary = 'Diciembre';
        } else {
            $monthAniversary = 'Desconocido';
        }

        return view('aniversary.aniversary', compact('employees', 'monthAniversary'));
    }
}
