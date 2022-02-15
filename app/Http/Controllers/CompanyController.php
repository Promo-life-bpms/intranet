<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\Position;
use Exception;

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

            if (!empty($employee->position)) {
                $position = $employee->position->name;
            } else {
                $position = 'Sin puesto asignado';
            }
            $tags = [];
            if ($position == 'Asistente de Dirección') {
                array_push($tags, 'assistant');
            }
            $img = 'https://avatarfiles.alphacoders.com/893/thumb-89303.gif';

            if ($employee->user->image) {
                $imgReplace = str_replace('\\','/',$employee->user->image);
                $img = asset('storage/profile/300x300' . explode("/", $imgReplace)[count(explode('/', $imgReplace))-1]);
            }

            $emp = [
                "id" => $employee->id,
                "pid" => $employee->jefe_directo_id,
                'tags' => $tags,
                "name" => $employee->user->name,
                "title" => $position,
                "img" => $img,
            ];

            array_push($dataEmployees, $emp);
        }

        return response()->json($dataEmployees);
    }

    public function getEmployeesByOrganization(Organization $organization)
    {
        $employees = $organization->employees;
        $dataEmployees = [];
        foreach ($employees as $employee) {
            $position = '';
            if (!empty($employee->position)) {
                $position = $employee->position->name;
            } else {
                $position = 'Sin puesto asignado';
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
            if (!empty($employee->position)) {
                $position = $employee->position->name;
            } else {
                $position = 'Sin puesto asignado';
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
