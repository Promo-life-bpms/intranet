<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'content','publication_id'];
    public $table = "comments";

    //conexion con el usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //relacion de muchos a uno - Many to one
    public function publication(){
        return $this->belongsTo(Publications::class, 'publication_id');
    }

}
