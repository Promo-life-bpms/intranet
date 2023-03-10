<?php

namespace App\Models\Soporte;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuariosSoporte extends Model
{
    use HasFactory;
    public $table = "soporte_usuarios_soporte";
    protected $fillable = [
        'id_users',
        'id_categorias',
    ];
    public function user()
    {
        return $this->hasMany(usort::class, 'id_users');
    }
    public function category()
    {
        return $this->hasMany(Categorsias::class, 'id_categorias');
    }
}
