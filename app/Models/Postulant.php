<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulant extends Model
{
    use HasFactory;
    public $table = "postulant";

    public function postulantDocumentation()
    {
        return $this->hasMany(PostulantDocumentation::class, 'postulant_id');
    }
}
    