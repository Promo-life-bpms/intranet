<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\Position;

class CompanyController extends Controller
{
    public function index()
    {
        $organizations = Organization::all();
        $departments = Department::all();
        return view('company.index', compact('departments'));
    }

    public function getPositions($id)
    {
        $positions = Position::all()->where("department_id", $id)->pluck("name", "id");
        return json_encode($positions);
    }

    public function getAllEmployees()
    {
        $employees = Employee::all();
        $dataEmployees = [];
        foreach ($employees as $employee) {
            $position = '';
            if ($employee->positions > 0) {
                $position = $employee->positions->name;
            }
            array_push($dataEmployees, [
                "id" => $employee->id,
                "pid" => $employee->jefe_directo_id,
                "Nombre" => $employee->user->name,
                "Puesto" => $position,
                "Photo" => 'https://www.pngall.com/wp-content/uploads/5/Profile-Male-PNG.png',
            ]);
        }
        // $dataEmployees = (object) $dataEmployees;
        return json_encode($dataEmployees);
    }

    public function getAllEmployeesByComapany(Organization $organization)
    {
        $employees = Employee::all();
        $dataEmployees = [];
        foreach ($employees as $employee) {
            $position = '';
            if (count($employee->positions) > 0) {
                $position = $employee->positions[0]->name;
            }
            array_push($dataEmployees, [
                "id" => $employee->id,
                "pid" => $employee->jefe_directo_id,
                "Nombre" => $employee->user->name,
                "Puesto" => $position,
                "Photo" => 'https://www.pngall.com/wp-content/uploads/5/Profile-Male-PNG.png',
            ]);
        }

        return json_encode($dataEmployees);
    }
}
