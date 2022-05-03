<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Communique;
use App\Models\Directory as ModelsDirectory;
use App\Models\Employee;
use App\Models\Manual;
use Directory;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;


class ApiController extends Controller
{
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function requestToken(Request $request): string
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($request->password != $user->password) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($request->device_name)->plainTextToken;
    }

    private function getUserAndToken(User $user, $device_name){
        return [
            'User' => $user,
            'Access-Token' => $user->createToken($device_name)->plainTextToken,
        ];
    }
 
     public function manuals(){
         $manuals = Manual::all();
         $data = [];
 
         foreach ($manuals  as $manual){
             $image = "";
             if($manual->img ==null){
                 $image = "img/pdf.png";
             }else{
                 $image = $manual->img;
             };
             array_push($data, (object)[
                 'id' => $manual->id,
                 'name'=> $manual->name,
                 'file' => $manual->file,
                 'img'=> $image,
             ]);
             
         }
         return $data;
     }
 
 
 
     public function monthEmployees()
     {
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, "https://evaluacion.promolife.lat/api/empleado-del-mes");
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $res = curl_exec($ch);
         curl_close($ch);
         $res = json_decode($res);
         $users = [];
         $employeesMonth = [];
         foreach ($res as $data) {
             try {
                 $user = User::where('email', '=', $data->email)->firstOrFail();
                 if ($user != null) {
                     $image='';
                     if($user->image==null){
                         $image="img/default_user.png";
                     }else{
                         $image=$user->image;
                     }
                     array_push($users, $user);
                     array_push($employeesMonth, (object)[
                         'id' => $user->id,
                         'name' => $user->name . ' ' . $user->lastname,
                         'position' => $data->puesto,
                         'star' => $data->star,
                         'photo' => $image
                     ]);
                 }
             } catch (Exception $e) {
             }
         }
         return $employeesMonth;
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
                 $monthAniversaryth = $birthday[1];
                 if ($monthAniversaryth == $date) {
                    
                     $image='';
                     if($employee->user->image==null){
                         $image="img/default_user.png";
                     }else{
                         $image=$employee->user->image;
                     }
 
                     array_push($employees, (object)[
                         'id' => $employee->user->id,
                         'name'=> $employee->user->name,
                         'lastname' => $employee->user->lastname,
                         'photo'=> $image,
                         'date' => $employee->date_admission->format('d-m-Y'),
                     ]);
                    
                 }
             }
         }
 
         return $employees;
     }
 
     public function birthday()
     {
         $carbon = new \Carbon\Carbon();
         $date = $carbon->now();
         $date = $date->format('m');
         $employees = [];
         foreach (Employee::all() as $employee) {
             if ($employee->birthday_date != null) {
                 $birthday = explode('-', $employee->birthday_date);
                 $monthAniversaryth = $birthday[1];
                 if ($monthAniversaryth == $date) {
 
                     $image='';
                     if($employee->user->image==null){
                         $image="img/default_user.png";
                     }else{
                         $image=$employee->user->image;
                     }
 
                     array_push($employees, (object)[
                         'id' => $employee->user->id,
                         'name'=> $employee->user->name,
                         'lastname' => $employee->user->lastname,
                         'photo' => $image,
                         'date' => $employee->birthday_date->format('d-m'),
                     ]);
 
                 }
             }
         }
 
         return $employees;
     }
 
     public function communicate(){
         $communicate = Communique::all();
 
         return $communicate;
     } 
     
     public function directory(){
         $user = User::all();
         $data = [];
         $directory = ModelsDirectory::all();
         $directory_data = [];
 
         foreach($user as $usr){
             foreach ($directory as $dir){
                 if($usr->id == $dir->user_id){
                     array_push($directory_data, (object)[
                         'type' => $dir->type,
                         'data'=> $dir->data,
                         'company' => $dir->companyName->name_company,
                     ]);
                 }
             }
 
             $image='';
             if($usr->image==null){
                 $image="img/default_user.png";
             }else{
                 $image=$usr->image;
             }
             
             array_push($data, (object)[
                 'id' => $usr->id,
                 'fullname'=> $usr->name . " ".$usr->lastname,
                 'email'=> $usr->email,
                 'photo' => $image,
                 'department'=> $usr->employee->position->department->name,
                 'position' => $usr->employee->position->name,
                 'data' =>  $directory_data,
             ]);
             $directory_data=[];
         }
 
         return $data;
     }
 
     public function organization($id){
         $user = User::all();
         $data = [];
         foreach($user as $usr){
             if ($usr->employee->position->department->id == $id){
                 $image='';
                 if($usr->image==null){
                     $image="img/default_user.png";
                 }else{
                     $image=$usr->image;
                 }
                 array_push($data, (object)[
                     'id' => $usr->id,
                     'name' => $usr->name,
                     'lastname'=> $usr->lastname,
                     'photo' => $image,
                     'department'=> $usr->employee->position->department->name,
                     'position' => $usr->employee->position->name,
                 ]);
             }
         }
 
         return $data;
    }
     

}
