<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class AniversaryController extends Controller
{
    public function birthday()
    {
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $date = $date->format('m');
        $employees = [];
        foreach (Employee::all() as $employee) {
            if ($employee->birthday_date != null) {
                $birthday = explode('-', $employee->birthday_date);
                $month = $birthday[1];
                if ($month == $date) {
                    array_push($employees, $employee);
                }
            }
        }
        return view('aniversary.birthday', compact('employees'));
    }

    public function aniversary()
    {
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $date = $date->format('m');
        $employees = [];
        foreach (Employee::all() as $employee) {
            if ($employee->date_admission != null) {
                $birthday = explode('-', $employee->date_admission);
                $month = $birthday[1];
                if ($month == $date) {
                    array_push($employees, $employee);
                }
            }
        }
        return view('aniversary.aniversary', compact('employees'));
    }
}
