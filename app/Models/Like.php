<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'publication_id'];
    public $table= "likes";

    //Relacion de muchos a uno -  Many to one
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //Relacion de muchos a uno -  Many to one
    public function publication()
    {
        return $this->belongsTo(Publications::class, 'publication_id');
    }
}
