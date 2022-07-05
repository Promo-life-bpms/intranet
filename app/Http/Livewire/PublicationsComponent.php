<?php

namespace App\Http\Livewire;

use App\Models\Publications;
use Livewire\Component;
use Livewire\WithPagination;

class PublicationsComponent extends Component
{
    use WithPagination;

    public $comment;

    public function render()
    {
        $publications = Publications::orderBy('created_at', 'desc')->simplePaginate(10); //get first 10 rows
        return view('livewire.publications-component', ['publications' => $publications]);
    }

    public function like($id)
    {
        $publications = Publications::find($id);
        auth()->user()->meGusta()->toggle($publications);
        // Enviar la notificacion:
    }

    public function comentar($publication_id)
    {
        $this->validate([
            'comment.' . $publication_id => 'required'
        ]);
        $publications = Publications::find($publication_id);
        $publications->comments()->create([
            'user_id' => auth()->user()->id,
            'content' => $this->comment[$publication_id]
        ]);

        $this->comment[$publication_id] = '';
    }
}
