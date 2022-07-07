<?php

namespace App\Http\Livewire;

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
        $commentCreated = $this->publication->comments()->create([
            'user_id' => auth()->user()->id,
            'content' => $this->comment
        ]);

        $this->comment = '';
        $this->dispatchBrowserEvent('comment-added', ['commentCreated' => $commentCreated]);
    }
}
