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
        return view('company.index', compact('departments', 'organizations'));
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
                "Photo" => $employee->user->image,
            ]);
        }
        // $dataEmployees = (object) $dataEmployees;
        return json_encode($dataEmployees);
    }

    public function getEmployeesByOrganization(Organization $organization)
    {
        $employees = $organization->employees;
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
                "Photo" => $employee->user->image,
            ]);
        }
        return response()->json($dataEmployees);
    }

    public function getEmployeesByDepartment(Department $department)
    {
        $employees = [];
        foreach ($department->positions as $position) {
            foreach ($position->getEmployees as $employee) {
                array_push($employees, $employee);
            }
        }
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
                "Photo" => $employee->user->image,
            ]);
        }
        return response()->json($dataEmployees);
    }
}
