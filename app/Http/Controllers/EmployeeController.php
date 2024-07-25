<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use DB;

class EmployeeController extends Controller
{
    public function getPositions($id)
    {
        $dep = Department::find($id);
        $positions = Position::all()->where("department_id", $id)->pluck("name", "id");
        $data = $dep->positions;
        $users = [];
        foreach ($data as $dat) {
            foreach ($dat->getEmployees as $emp) {
                if($emp->user->status == 1){
                    $users["{$emp->user->id}"] = $emp->user->name . ' ' . $emp->user->lastname;
                }
            }
        }
        return response()->json(['positions' => $positions, 'users' => $users,]);
    }
}
