<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostulantDocumentation extends Model
{
    use HasFactory;
    public $table = "postulant_documentation";

    public function postulant()
    {
        return $this->belongsTo(Postulant::class, 'postulant_id');
    }
}
