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
    // public function user()
    // {
    //     return $this->hasMany(user::class, 'id_users');
    // }
    // public function category()
    // {
    //     return $this->hasMany(Categorias::class, 'id_categorias');
    // }

    public function asignacion()
    {
        return $this-> belongsToMany(User::class,'id_user');
    }
    
}
