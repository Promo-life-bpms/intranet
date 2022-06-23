<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publications extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content_publication',
        'photo_public'
    ];

    //Conexion con el usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //Relacion one to many- relacion uno a muchos
    public function comments()
    {
        return $this->hasMany(Comment::class, 'publication_id')->orderBy('id', 'desc');
    }


    //likes que ha recibido una publicacion
    public function like()
    {
        return $this->belongsToMany(User::class, 'likes', 'publication_id', 'user_id' );
    }
    //Imagenes de la publicacion
    public function files(){
        return $this->hasMany(Media::class, 'publication_id');
    }
}
