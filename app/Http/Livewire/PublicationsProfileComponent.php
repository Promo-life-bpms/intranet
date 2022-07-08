<?php

namespace App\Http\Livewire;

use App\Models\Publications;
use Livewire\Component;

class PublicationsProfileComponent extends Component
{
    public $user;

    public function render()
    {
        $publications = $this->user->publications()->orderBy('created_at', 'desc')->simplePaginate(10); //get first 10 rows
        return view('livewire.publications-component', ['publications' => $publications]);
    }
}
