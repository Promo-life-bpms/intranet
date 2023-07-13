<?php

namespace App\Http\Livewire;

use App\Http\Controllers\FirebaseNotificationController;
use App\Models\Publications;
use Livewire\Component;

class LikeComponent extends Component
{
    public $publication, $countLikes;
    public function render()
    {
        return view('livewire.like-component');
    }

    public function like($id)
    {
        $firebase_notification = new FirebaseNotificationController();
        $firebase_notification->likePublication($this->publication->user_id);
        auth()->user()->meGusta()->toggle( $this->publication);
        $this->publication = Publications::find($this->publication->id);
        
    }
}
