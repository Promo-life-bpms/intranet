<?php

namespace App\Http\Controllers\API;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Communique;
use App\Models\Directory as ModelsDirectory;
use App\Models\Employee;
use App\Models\Like;
use App\Models\Manual;
use App\Models\Media;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Publications;
use App\Models\Request as ModelsRequest;
use App\Models\RequestCalendar;
use App\Models\Vacations;
use App\Notifications\MessageNotification;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use JetBrains\PhpStorm\Internal\ReturnTypeContract;
use Illuminate\Support\Facades\File;
use Cache;
use Illuminate\Support\Arr;

use function PHPUnit\Framework\isEmpty;

class ApiController extends Controller
{
    public function getAllUsers()
    {
        $users = User::where('status', 1)->get();
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

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();

        $user->createToken($request->device_name)->plainTextToken;

        $token =  DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->value('token');
        return $token;
    }

    public function getUser($hashedToken)
    {

        $token = DB::table('personal_access_tokens')->where('token', $hashedToken)->first();
        $user_id = $token->tokenable_id;
        $user = User::where('id', $user_id)->get();
        $vacations = DB::table('vacations_availables')->where('users_id', $user_id)->where('period', '<>', 3)->sum('dv');
        $data = [];

        if ($vacations == null) {
            $vacations = 0;
        }

        foreach ($user as $usr) {

            $image = '';
            if ($usr->image == null) {
                $image = "img/default_user.png";
            } else {
                $image = $usr->image;
            }
            $expiration= [];

            $vacation_duration= Vacations::all()->where('users_id',$usr->id);

            foreach($vacation_duration as $vacation){
                if($vacation == null || $vacation == []){
                    array_push($expiration, (object)[
                        'daysAvailables' => "Sin dias disponibles",
                        'cutoffDate' => "Sin fecha de corte disponible",
                    ]);
                }else{
                    array_push($expiration, (object)[
                        'daysAvailables' => strval(floor($vacation->days_availables)) ,
                        'cutoffDate' => date('d-m-Y', strtotime( $vacation->cutoff_date)),
                    ]);
                }
            }

            array_push($data, (object)[
                'id' => $usr->id,
                'fullname' => $usr->name . " " . $usr->lastname,
                'email' => $usr->email,
                'photo' => $image,
                'department' => $usr->employee->position->department->name,
                'position' => $usr->employee->position->name,
                'daysAvailables' => intval($vacations),
                'expiration'=>$expiration,
            ]);
        }

        return $data;
    }


    public function manuals()
    {
        $manuals = Manual::all();
        $data = [];

        foreach ($manuals  as $manual) {
            $image = "";
            if ($manual->img == null) {
                $image = "img/pdf.png";
            } else {
                $image = $manual->img;
            };
            array_push($data, (object)[
                'id' => $manual->id,
                'name' => $manual->name,
                'file' => $manual->file,
                'img' => $image,
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
                    $image = '';
                    if ($user->image == null) {
                        $image = "img/default_user.png";
                    } else {
                        $image = $user->image;
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

                    $image = '';
                    if ($employee->user->image == null) {
                        $image = "img/default_user.png";
                    } else {
                        $image = $employee->user->image;
                    }
                    $month = new \Carbon\Carbon();
                    $totalAdmission = $month->format('Y');

                    $totalYears= $employee->date_admission->format('Y');


                    array_push($employees, (object)[
                        'id' => $employee->user->id,
                        'name' => $employee->user->name,
                        'lastname' => $employee->user->lastname,
                        'photo' => $image,
                        'date' => $totalAdmission-$totalYears ,
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

                    $image = '';
                    if ($employee->user->image == null) {
                        $image = "img/default_user.png";
                    } else {
                        $image = $employee->user->image;
                    }

                    array_push($employees, (object)[
                        'id' => $employee->user->id,
                        'name' => $employee->user->name,
                        'lastname' => $employee->user->lastname,
                        'photo' => $image,
                        'date' => $employee->birthday_date->format('d-m'),
                    ]);
                }
            }
        }

        return $employees;
    }

    public function communicate()
    {
        $communicate = Communique::all();

        return $communicate;
    }

    public function directory()
    {
        $user = User::where('status', 1)->get();
        $data = [];
        $directory = ModelsDirectory::all();
        $directory_data = [];

        foreach ($user as $usr) {

            $userOnline = false;

            if (Cache::has('user-is-online-' . $usr->id)) {
                $userOnline = true;
            }

            foreach ($directory as $dir) {
                if ($usr->id == $dir->user_id) {
                    array_push($directory_data, (object)[
                        'type' => $dir->type,
                        'data' => $dir->data,
                        'company' => $dir->companyName->name_company,
                    ]);
                }
            }

            $image = '';
            if ($usr->image == null) {
                $image = "img/default_user.png";
            } else {
                $image = $usr->image;
            }

            array_push($data, (object)[
                'id' => $usr->id,
                'fullname' => $usr->name . " " . $usr->lastname,
                'email' => $usr->email,
                'photo' => $image,
                'department' => $usr->employee->position->department->name,
                'position' => $usr->employee->position->name,
                'onlineStatus' => $userOnline,
                'data' =>  $directory_data,
            ]);
            $directory_data = [];
        }

        return $data;
    }

    public function organization($id)
    {
        $user = User::where('status', 1)->get();
        $data = [];
        foreach ($user as $usr) {
            if ($usr->employee->position->department->id == $id) {
                $image = '';
                if ($usr->image == null) {
                    $image = "img/default_user.png";
                } else {
                    $image = $usr->image;
                }
                array_push($data, (object)[
                    'id' => $usr->id,
                    'name' => $usr->name,
                    'lastname' => $usr->lastname,
                    'photo' => $image,
                    'department' => $usr->employee->position->department->name,
                    'position' => $usr->employee->position->name,
                ]);
            }
        }

        return $data;
    }


    public function getRequest($hashedToken)
    {

        $token = DB::table('personal_access_tokens')->where('token', $hashedToken)->first();
        $user_id = $token->tokenable_id;
        $request = ModelsRequest::all()->where('employee_id', $user_id);
        $vacations = DB::table('vacations_availables')->where('users_id', $user_id)->where('period', '<>', 3)->sum('dv');

        $data = [];
        $start = "";
        $end = "";

        if ($vacations == null) {
            $vacations = 0;
        }

        foreach ($request as $req) {

            $days = "";

            if ($req->start == null) {
                $start = "Sin especificar";
            } else {
                $start = $req->start;
            }

            if ($req->end == null) {
                $end = "Sin especificar";
            } else {
                $end = $req->end;
            }

            if ($req->direct_manager_status == "Rechazada" || $req->human_resources_status == "Rechazada") {
                $date =  DB::table('request_rejected')->where('users_id', $req->employee_id)->where('requests_id', $req->id)->get();
            } else {
                $date = DB::table('request_calendars')->where('users_id', $req->employee_id)->where('requests_id', $req->id)->get();
            }

            foreach ($date as  $calendar) {
                $days = $days . "," . $calendar->start;
            }

            $days = substr($days, 1);

            array_push($data, (object)[
                'id' => $req->id,
                'employeeID' => $req->employee_id,
                'typeRequest' => $req->type_request,
                'payment' => $req->payment,
                'payment' => $req->payment,
                'start' => $start,
                'end' => $end,
                'reason' => $req->reason,
                'directManagerId' => $req->direct_manager_id,
                'directManagerStatus' => $req->direct_manager_status,
                'humanResourcesStatus' => $req->human_resources_status,
                'visible' => $req->visible,
                'days' => $days,
                'daysAvailables' => intval($vacations),
            ]);
        }


        return $data;
    }


    public function postRequest(Request $request)
    {
        $token = DB::table('personal_access_tokens')->where('token', $request->token)->first();
        $user_id = $token->tokenable_id;
        $employee = Employee::all()->where('user_id',$user_id);

        if($token !=null || $token !=""){
            $date= date("G:i:s", strtotime($request->start));
            $manager = "";
            foreach($employee as $emp){
                $manager = $emp->jefe_directo_id;
            }

            $req = new ModelsRequest();
            $req->employee_id = $user_id;
            $req->type_request = $request->typeRequest;
            $req->payment = $request->payment;
            $req->reason = $request->reason;
            $req->start = $date;
            $req->end = null;
            $req->direct_manager_id = $manager;
            $req->direct_manager_status = "Pendiente";
            $req->human_resources_status = "Pendiente";
            $req->visible = 1;
            $req->save();

            $days = collect( $request->days);
            $daySelected= str_replace (array('["', '"]'), '' , $days);
            $tag_array = explode(',', $daySelected );

            foreach($tag_array as $day){
                $daySelected2= str_replace (array('[', ']'), '' , $day);

                $dayInt = intval($daySelected2);

                $date = DateTime::createFromFormat('dmY', $dayInt);

                $request_calendar = new RequestCalendar();
                $request_calendar->title = "Día seleccionado";
                $request_calendar->start =  $date->format('Y-m-d');
                $request_calendar->end = $date->format('Y-m-d');
                $request_calendar->users_id = $user_id;
                $request_calendar->requests_id =$req->id;
                $request_calendar->save();

            }
            $data_send = [
                "id"=>$req->id,
                "employee_id"=>$user_id,
                "direct_manager_status"=>"Pendiente",
                "human_resources_status"=>"Pendiente"
            ];

            $notification = new Notification();
            $notification->id = $req->id;
            $notification->type = "App\Notifications\RequestNotification";
            $notification->notifiable_type = "App\Models\User";
            $notification->notifiable_id = $manager;
            $notification->data = json_encode($data_send);
            $notification->save();

        }

        return  true;


    }


    public function getPublications($hashedToken)
    {
        $token = DB::table('personal_access_tokens')->where('token', $hashedToken)->first();
        $user_id = $token->tokenable_id;

        $publications = Publications::orderBy("created_at", "desc")->get();
        $data = [];
        $likes = DB::table('likes')->get();
        $comments = Comment::all();

        foreach ($publications as $pub) {

            $created = $pub->created_at->diffForHumans(null, false, false, 1);;
            $fullname = "";
            $totalLikes = 0;
            $photo = "";
            $isLike =false;
            $user= User::all()->where('id', $pub->user_id);
            $publi_comments= [];

            foreach ($likes as $like) {
                if ($like->publication_id == $pub->id) {
                    $totalLikes = $totalLikes + 1;
                    if($like->user_id == $user_id){

                        $isLike = true;
                    }
                }

            }

            foreach($user as $usr){
                $fullname = $usr->name . " " . $usr->lastname;

                $image = '';
                if ($usr->image == null) {
                    $image = "img/default_user.png";
                } else {
                    $image = $usr->image;
                }
            }

            foreach($comments as $com){

                if($com->publication_id == $pub->id){

                    $comment_user = User::all()->where('id',$com->user_id);
                    foreach($comment_user as $com_user){

                        $com_fullname = $com_user->name . " " . $com_user->lastname;

                        $com_image = '';
                        if ($com_user->image == null) {
                            $com_image = "img/default_user.png";
                        } else {
                            $com_image = $com_user->image;

                        }

                        array_push($publi_comments, (object)[

                            'id' => $com->publication_id,
                            'userName' => $com_fullname,
                            'photo' => $com_image,
                            'content' => $com->content,
                        ]);
                    }


                }
            }

            $publicationImageData =[];
            $publicationImage = Media::all()->where('publication_id',$pub->id);

            if (count($publicationImage) >0) {
                $publicationImageData =[];
                foreach($publicationImage as $media){
                    array_push($publicationImageData, (object)[
                        'typeFile' =>  $media->type_file,
                        'resource' => "storage/posts/". $media->resource,
                    ]);
                }

            }else{
                array_push($publicationImageData, (object)[
                    'typeFile' => "no data",
                    'resource' => "no data",
                ]);
            }

            if($publi_comments == []){
                array_push($publi_comments, (object)[

                    'id' => $pub->id,
                    'userName' => "no data",
                    'photo' => "no data",
                    'content' => "no data",
                ]);
            }
            array_push($data, (object)[
                'id' => $pub->id,
                'userId' => $pub->user_id,
                'photo' => $image,
                'userName' => $fullname,
                'created' => $created,
                'contentPublication' => $pub->content_publication,
                'photoPublication' => $publicationImageData,
                'likes' => $totalLikes,
                'isLike'=>$isLike,
                'comments'=>$publi_comments,
            ]);
        }

        return  $data;
    }

    public function postPublications(Request $request){

        $token = DB::table('personal_access_tokens')->where('token', $request->token)->first();
        $user_id = $token->tokenable_id;

        if($user_id!=null || $user_id !=[]){

            $data = new Publications();
            $data->id = $request->id;
            $data->user_id = $user_id;
            $data->content_publication = $request->contentPublication;
            $data->photo_public = "sin foto";
            $data->save();

            return $token;

        }

    }

    public function postLike(Request $request){
        $token = DB::table('personal_access_tokens')->where('token', $request->token)->first();
        $user_id = $token->tokenable_id;

        $like = new Like();
        $like->user_id = $user_id;
        $like->publication_id = $request->publicationID;
        $like->save();

    }

    public function postUnlike(Request $request){
        $token = DB::table('personal_access_tokens')->where('token', $request->token)->first();
        $user_id = $token->tokenable_id;

        DB::table('likes')->where('user_id',  $user_id)->where('publication_id',$request->publicationID)->delete();
    }

    public function postComment(Request $request){
        $token = DB::table('personal_access_tokens')->where('token', $request->token)->first();
        $user_id = $token->tokenable_id;

        if($user_id!=null || $user_id !=[]){

            $comment = new Comment();
            $comment->user_id =$user_id;
            $comment->publication_id = $request->publicationID;
            $comment->content =$request->content;
            $comment->save();

            return true;
        }

    }

    public function getProfile($id){
        $user = User::all()->where('id',$id);
        $publications = Publications::all()->where('user_id',$id);
        $likes = DB::table('likes')->get();
        $comments = Comment::all();
        $data = [];
        $user_publication = [];

        foreach ($user as $usr) {

            $image = '';
            if ($usr->image == null) {
                $image = "img/default_user.png";
            } else {
                $image = $usr->image;
            }

            if(count($publications)==0 ){
                $publi_comments= [];

                array_push($publi_comments, (object)[

                    'id' => "sin datos",
                    'userName' => "sin datos",
                    'photo' => "sin datos",
                    'content' => "sin datos",
                ]);

            array_push($user_publication, (object)[
                'id' => "sin datos",
                'userId' => "sin datos",
                'photo' => "sin datos",
                'userName' => "sin datos",
                'created' => "sin datos",
                'contentPublication' => "sin datos",
                'photoPublication' => "sin datos",
                'likes' => "sin datos",
                'isLike'=>"sin datos",
                'comments'=>$publi_comments,

            ]);
            }else{
                foreach ($publications as $pub) {

                    $created = $pub->created_at->diffForHumans(null, false, false, 1);;
                    $fullname = "";
                    $totalLikes = 0;
                    $photo = "";
                    $isLike =false;
                    $user= User::all()->where('id', $pub->user_id);
                    $publi_comments= [];

                    foreach ($likes as $like) {
                        if ($like->publication_id == $pub->id) {
                            $totalLikes = $totalLikes + 1;
                            if($like->user_id == $id){

                                $isLike = true;
                            }
                        }

                    }

                    foreach($user as $usr){
                        $fullname = $usr->name . " " . $usr->lastname;

                        $image = '';
                        if ($usr->image == null) {
                            $image = "img/default_user.png";
                        } else {
                            $image = $usr->image;
                        }
                    }

                    foreach($comments as $com){

                        if($com->publication_id == $pub->id){

                            $comment_user = User::all()->where('id',$com->user_id);
                            foreach($comment_user as $com_user){

                                $com_fullname = $com_user->name . " " . $com_user->lastname;

                                $com_image = '';
                                if ($com_user->image == null) {
                                    $com_image = "img/default_user.png";
                                } else {
                                    $com_image = $com_user->image;

                                }

                                array_push($publi_comments, (object)[

                                    'id' => $com->publication_id,
                                    'userName' => $com_fullname,
                                    'photo' => $com_image,
                                    'content' => $com->content,
                                ]);
                            }


                        }
                    }
                    if ($pub->photo_public == "") {
                        $photo = "no photo";
                    } else {
                        $photo = $pub->photo_public;
                    }

                    if($publi_comments == []){
                        array_push($publi_comments, (object)[

                            'id' => $pub->id,
                            'userName' => "sin datos",
                            'photo' => "sin datos",
                            'content' => "sin datos",
                        ]);
                    }
                    array_push($user_publication, (object)[
                        'id' => $pub->id,
                        'userId' => $pub->user_id,
                        'photo' => $image,
                        'userName' => $fullname,
                        'created' => $created,
                        'contentPublication' => $pub->content_publication,
                        'photoPublication' => $photo,
                        'likes' => $totalLikes,
                        'isLike'=>$isLike,
                        'comments'=>$publi_comments,

                    ]);
                }


            }

            array_push($data, (object)[
                'id' => $usr->id,
                'fullname' => $usr->name . " " . $usr->lastname,
                'email' => $usr->email,
                'photo' => $image,
                'department' => $usr->employee->position->department->name,
                'position' => $usr->employee->position->name,
                'publications' =>$user_publication,
            ]);
        }

        return $data;

    }

    public function postDeleteRequest(Request $request){
        $token = DB::table('personal_access_tokens')->where('token', $request->token)->first();
        $user_id = $token->tokenable_id;

        if($user_id!=null){
            DB::table('request_calendars')->where('requests_id',  $request->requestID)->delete();
            DB::table('requests')->where('id',  $request->requestID)->delete();
        }
    }

    public function postDeletePublication(Request $request){
        $token = DB::table('personal_access_tokens')->where('token', $request->token)->first();
        $user_id = $token->tokenable_id;

        if($user_id!=null){

            $publication = Publications::all()->where('id',$request->publciationID);

            foreach ($publication as $pub){
                if($pub->photo_public!=null || $pub->photo_public!=""){
                    File::delete($pub->photo_public);
                }
            }

            DB::table('likes')->where('publication_id',  intval($request-> publciationID))->delete();
            DB::table('comments')->where('publication_id', intval($request-> publciationID))->delete();
            DB::table('publications')->where('id', intval($request-> publciationID))->delete();

            return  true;
        }

        return  false;
    }

    public function getUserMessages($hashedToken){
        $token = DB::table('personal_access_tokens')->where('token', $hashedToken)->first();
        $user_id = $token->tokenable_id;

        //Obtiene todos los empleados que tienen conversarion con el usuario
        $messages = DB::table('messages')->where('transmitter_id',$user_id)->orWhere('receiver_id',$user_id)->orderBy('created_at', 'desc')->get();

        $uniqueTransmitter = $messages->unique('transmitter_id');
        $uniqueReceiver = $messages->unique('receiver_id');

        $uniqueTransmitterList = [];
        $uniqueReceiverList = [];

        //Se trae el id del objeto a una lista
        foreach($uniqueTransmitter as $uniqueTrans){
            array_push($uniqueTransmitterList, $uniqueTrans->transmitter_id);
        }
        //Se trae el id del objeto a una lista
        foreach($uniqueReceiver as $unique){
            array_push($uniqueReceiverList, $unique->receiver_id);
        }
        //Se unen todos los usuarios que han tenido algun mensaje con el solicitante, ya sea de emisor o receptor
        $totalUsers = array_unique(array_merge($uniqueTransmitterList,$uniqueReceiverList));
        $data =[];
        $allUsers = User::all()->whereIn("id",$totalUsers);


        if($messages->count()==0){

            array_push($data, (object)[
                'id' => $user_id,
                'fullname' =>"no data" ,
                'email' => "no data",
                'photo' =>"no data",
                'department' => "no data",
                'position' => "no data",
                'conversation'=>"no data",
                'createdAt' => "no data",
                'time' => "no data",
            ]);

            return $data;
        }else{

            foreach($allUsers as $user){
              /*   dd(strval($user->id) ); */
                if($messages->contains('transmitter_id',$user->id) || $messages->contains('receiver_id',$user->id)){

                    $userID = $user->id;
                    $myUserID= $user_id;
                    $lastMessage= Message::where(function ($query) use ($userID) {
                        return $query->where('transmitter_id', '=', $userID)
                              ->orWhere('receiver_id', '=', $userID);
                    })->where(function ($query) use ($myUserID) {
                        return $query->where('transmitter_id', '=', $myUserID)
                              ->orWhere('receiver_id', '=', $myUserID);
                    })->orderBy('created_at', 'desc')->first();


                    $image = '';
                    if ($user->image == null) {
                        $image = "img/default_user.png";
                    } else {
                        $image = $user->image;
                    }
                    if($lastMessage != null){
                        array_push($data, (object)[
                            'id' => $user->id,
                            'fullname' => $user->name . " " . $user->lastname,
                            'email' => $user->email,
                            'photo' => $image,
                            'department' => $user->employee->position->department->name,
                            'position' => $user->employee->position->name,
                            'conversation'=>$lastMessage->message,
                            'createdAt' => $lastMessage->created_at,
                            'time' => date('H:i', strtotime($lastMessage->created_at)),
                        ]);
                    }

                }

            }

            return $data;

        }

    }

    public function postUserMessages(Request $request){
        $token = DB::table('personal_access_tokens')->where('token', $request->token)->first();
        $user_id = $token->tokenable_id;

        $conversationUserID = $request->conversationUserID;


        $mensajes = DB::table('messages')
            ->where('transmitter_id',$user_id)
            ->where('receiver_id', $conversationUserID);


        $mensajesEnviados = DB::table('messages')
            ->where('receiver_id', $user_id)
            ->where('transmitter_id', $conversationUserID)->union($mensajes)->orderBy('created_at', 'asc')->get();

        $data =[];

        if($mensajesEnviados->count()==0){

            array_push($data, (object)[
                'id' => $user_id,
                'transmitterID' => $user_id,
                'receiverID' => $user_id,
                'message' => "no data",
                'created' => "no data",
                'updated' => "no data",
            ]);

            return $data;
        }else{
            foreach($mensajesEnviados as $mensaje){
                array_push($data, (object)[
                    'id' => $user_id,
                    'transmitterID' => $mensaje->transmitter_id,
                    'receiverID' => $mensaje->receiver_id,
                    'message' => $mensaje->message,
                    'created' => date('H:i', strtotime($mensaje->created_at)),
                    'updated' => date('H:i', strtotime($mensaje->created_at)),
                ]);
            }

            return $data;
        }

    }


    public function postConversation(Request $request){
        $token = DB::table('personal_access_tokens')->where('token', $request->token)->first();
        $user_id = $token->tokenable_id;
        $user_name = DB::table('users')->where('id',$user_id)->value('name');
        $user_lastname = DB::table('users')->where('id',$user_id)->value('lastname');
        $user_image = DB::table('users')->where('id',$user_id)->value('image');

        //Crear mensaje en la BD
        $message = new Message();
        $message->transmitter_id =  $user_id;
        $message->receiver_id = intval($request->receiverID);
        $message->message =  $request->message;
        $message->save();

        $data_send = [
            'emisor' => $user_id,
            'message' => $request->message,
            'transmitter_name' => $user_name. " ".$user_lastname ,
            'image' => $user_image,
        ];


        $notification = new Notification();
        $notification->id =uniqid();
        $notification->type = "App\Notifications\MessageNotification";
        $notification->notifiable_type = "App\Models\User";
        $notification->notifiable_id = intval($request->receiverID) ;
        $notification->data =json_encode($data_send);
        $notification->save();

        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $date = $date->format('Y-m-d H:i:s');

        broadcast(new MessageSent( $request->message, intval($request->receiverID), $user_id ,$user_name. " ".$user_lastname, $date ))->toOthers();


        return true;
    }


    public function postUpdatePublication(Request $request){

        
        DB::table('publications')->where('id', $request->publicationID)->update(['content_publication' => $request->contentPublication]);

    }

}
