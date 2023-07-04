<?php

namespace App\Http\Livewire;

use App\Http\Controllers\FirebaseNotificationController;
use Livewire\Component;

class CommentComponent extends Component
{
    public $publication, $comment;
    public function render()
    {
        return view('livewire.comment-component');
    }

    public function comentar()
    {
        $this->validate([
            'comment'  => 'required'
        ]);

        $firebase_notification = new FirebaseNotificationController();
        $firebase_notification->commentaryPublication($this->publication->user_id, auth()->user()->name . ' ' );

        $commentCreated = $this->publication->comments()->create([
            'user_id' => auth()->user()->id,
            'content' => $this->comment
        ]);

        $this->comment = '';
        $this->dispatchBrowserEvent('comment-added', ['commentCreated' => $commentCreated]);
    }
}
