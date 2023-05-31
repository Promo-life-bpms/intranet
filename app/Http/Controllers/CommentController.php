<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Publications;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        //obtener los datos del comentario
        request()->validate([
            'content'=>['required', 'string']
        ]);

        //Crear comentario
        $comment = Comment::create([
            'user_id'=>auth()->user()->id,
            'publication_id'=>$request->id_publication,
            'content'=> $request->content
        ]);

        $publication = Publications::where('id',$request->id_publication)->get()->last();

        $firebase_notification = new FirebaseNotificationController();
        $firebase_notification->commentaryPublication($publication->user_id, auth()->user()->name . ' ' . auth()->user()->lastname);

        //event(new CommentEvent($comment));
        return redirect()->action(HomeController::class);
    }
}
