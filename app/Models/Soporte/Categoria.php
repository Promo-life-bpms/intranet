<?php

namespace App\Models\Soporte;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{

    use HasFactory;
    public $table = "soporte_categorias";
    protected $fillable = [
        'name',
        'status',
        'slug',
    ];

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'soporte_usuarios_soporte',  'categorias_id', 'users_id');
    }

}
