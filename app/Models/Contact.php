<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    public $table = "contact";

    protected $fillable = [
        'num_tel',
        'correo1',
        'correo2',
        'correo3',
        'correo4',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
