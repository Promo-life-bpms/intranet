<?php

namespace App\Models\Soporte;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuariosSoporte extends Model
{
    use HasFactory;
    public $table = "soporte_usuarios_soporte";
    protected $fillable = [
        'users_id',
        'categorias_id',
    ];

    

}
