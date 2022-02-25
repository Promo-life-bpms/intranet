<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users);
    }
}
